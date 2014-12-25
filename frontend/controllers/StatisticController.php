<?
namespace frontend\controllers;

use Yii;
use yii\base\Controller;
use yii\base\ErrorException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\web\Response;
use common\models\Campaign;
use common\models\Donation;
use frontend\models\helpers\PullrStatistic;
use common\components\PullrUtils;

use common\models\User;
use \ritero\SDK\TwitchTV\TwitchSDK;
use frontend\models\helpers\TwitchChannelHelper;
use frontend\models\helpers\CampaignHelper;

class StatisticController extends FrontendController {

    public $twitch;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','amount_raised', 'api', 'featured_campaign_api', 'campaign_data'],
                        'allow' => true
                    ],
                    [
                        'allow' => true,
                        'roles' => ['frontend'],
                    ],
                ],
            ],
        ];
    }

    public function init() {
        \Yii::$app->response->headers->set('Access-Control-Allow-Origin', '*');
        \Yii::$app->response->headers->set('Access-Control-Allow-Methods', 'GET, PUT, POST, DELETE, OPTIONS');
        \Yii::$app->response->headers->set('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, streamboardToken');

        if (\Yii::$app->request->isOptions)
        {
            echo 'Invalid request';
            \Yii::$app->end();
        }
        $this->twitch = new TwitchSDK();
        return parent::init();
    }

    public function getAmountRaised()
    {
        $pullrStatistic = new PullrStatistic();
        $cacheKey = 'pullr_statistic_amount_raised';
        $result = \Yii::$app->cache->get($cacheKey);
        if ( false === $result ) {
            $totalAmount =
            $result = [
                'totalAmount' => PullrUtils::formatNumber($pullrStatistic->getTotalDonation()),
                'totalCharityAmount' => PullrUtils::formatNumber($pullrStatistic->getTotalDonationFromCharityCampaign()),
                'totalPersonalAmount' => PullrUtils::formatNumber($pullrStatistic->getTotalDonationFromPersonalCampaign())
            ];
            \Yii::$app->cache->set($cacheKey, $result, 1800);
        }
        return $result;
    }

    public function actionAmount_raised()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $result = $this->getAmountRaised();
        return $result;
    }

    public function actionCampaign_data()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ( ! Yii::$app->getRequest()->isPost ) {
            return [];
        }
        $campaigns = CampaignHelper::getFeaturedCampaigns();
        $results = array();
        $twitchChannelHelper = new TwitchChannelHelper();
        $twitchChannelHelper->setAllowCache(true);
        foreach($campaigns as $campaign) {
            $campaignArray = $campaign->toArray();
            $campaignArray['goalAmountFormatted'] = '$' . PullrUtils::formatNumber($campaign['goalAmount'], 2);
            $campaignArray['amountRaisedFormatted'] = '$' . PullrUtils::formatNumber($campaign['amountRaised'], 2);
            $campaignArray['percentageOfGoal'] = $campaign['goalAmount'] > 0 ? round($campaign['amountRaised'] / $campaign['goalAmount'] * 100) : 0;
            if (($campaign->donationDestination == Campaign::DONATION_PARTNERED_CHARITIES) && ($campaign->type === Campaign::TYPE_CHARITY_FUNDRAISER) && $campaign->charity) {
                $campaignArray['charityName'] = $campaign->charity->name;
            } else {
                $campaignArray['charityName'] = $campaign->customCharity;
            }
            $campaignArray['channels'] = $twitchChannelHelper->getCampaignChannel($campaign);
            $campaignArray['donationFormUrl'] = '/' . $campaign->user->getUrl() . $campaign->alias . '/donate';
            $campaignArray['campaignPageUrl'] = '/' . $campaign->user->getUrl() . $campaign->alias;
            $campaignArray['backgroundImageUrl'] = $campaign->backgroundImageUrl;
            if ($campaign->layoutType == Campaign::LAYOUT_TYPE_TEAM) {
                $campaignArray['twitchTeam'] = $twitchChannelHelper->getTeamObject($campaign->channelTeam);
            }
            $results[] = $campaignArray;
        }
        return $results;
    }


    public function actionApi()
    {
        echo $this->renderFile('@frontend/views/api/statistic.js');
    }

    public function actionFeatured_campaign_api()
    {
        echo $this->renderFile('@frontend/views/api/featured_campaign.js');
    }
}
