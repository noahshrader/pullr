<?
namespace frontend\models\helpers;
use \ritero\SDK\TwitchTV\TwitchSDK;
use common\models\Campaign;

class TwitchChannelHelper {
    /*
    * $twitch TwitchSDK
    */
    protected $twitch;

    /*
    * @todo
    * Cache channel request or not
    * We will implement later
    */
    protected $allowCache;

    const CACHE_DURATION = 1800;

    public function __construct()
    {
        $this->twitch = new TwitchSDK();;
        $this->allowCache = false;
    }

    /*
    *
    */
    public function setAllowCache($allowCache)
    {
        $this->allowCache = $allowCache;
    }

    public function getCache($key)
    {
        if ( $this->allowCache && $cache = \Yii::$app->cache->get($key)) {
            return $cache;
        }
        return false;
    }

    public function setCache($key, $data)
    {
        if ( $this->allowCache ) {
            return \Yii::$app->cache->set($key, $data, static::CACHE_DURATION);
        }
        return false;
    }

    /*
    * Get single channel data by name
    * @param string $channelName channel name
    */
    public function getSingleChannel($channelName)
    {
        $key = 'TwitchChannelHelper_getSingleChannel_' . $channelName;
        $result = $this->getCache($key);
        if ( ! $result) {
            $result = $this->twitch->channelGet($channelName);
            $this->setCache($key, $result);
        }
        return $result;


    }

    /*
    * Get team channel data
    * @param string $channelTeamName
    */
    public function getTeamChannel($channelTeamName)
    {
        $key = 'TwitchChannelHelper_getTeamChannel_' . $channelTeamName;
        $results = $this->getCache($key);
        if ( ! $results) {
            $membersList = $this->twitch->teamMembersAll($channelTeamName);
            $offlines = [];
            $onlines = [];
            $results = [];
            foreach ($membersList as $member) {
                if ($member->channel->status == 'offline') {
                    $offlines[] = $member->channel;
                } else {
                    $onlines[] = $member->channel;
                }
            }
            shuffle($onlines);
            $results = array_merge($onlines, $offlines);
            $this->setCache($key, $results);
        }
        return $results;
    }

    /*
    * Get channels from channel name list
    * @param array $channelNameList
    * @return array result
    */
    public function getMultiChannel(array $channelNameList)
    {
        $key = 'TwitchChannelHelper_getMultiChannel_' . join('_', $channelNameList);
        $results = $this->getCache($key);
        if ( ! $results ) {
            foreach ($channelNameList as $channelName) {
                $results[] = $this->twitch->channelGet($channelName);
            }
            $this->setCache($key, $results);
        }
        return $results;
    }

    /*
    * Get channels of a single campaign
    */
    public function getCampaignChannel(Campaign $campaign)
    {
        $result = [];
        switch ($campaign->layoutType) {
            case Campaign::LAYOUT_TYPE_SINGLE:
                if ( ! empty($campaign->channelName)) {
                    $result = $this->getSingleChannel($campaign->channelName);
                }
            break;
            case Campaign::LAYOUT_TYPE_MULTI:
                $channelNameList = [];
                $memberChannels = $campaign->getTeams()->all();
                foreach ($memberChannels as $memberChannel) {
                    $channelNameList[] = $memberChannel->name;
                }
                if (count($channelNameList) > 0) {
                    $result = $this->getMultiChannel($channelNameList);
                }
            break;
            case Campaign::LAYOUT_TYPE_TEAM:
                if ( ! empty($campaign->channelTeam)) {
                    $result = $this->getTeamChannel($campaign->channelTeam);
                }

            break;
            default:
                throw new Exception('Invalid layout type');
        }
        return $result;
    }

}