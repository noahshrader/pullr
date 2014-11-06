<?php

namespace frontend\controllers;

use common\models\base\BaseImage;
use common\models\Plan;
use yii\web\Response;
use yii\base\Exception;
use yii\web\ForbiddenHttpException;
use common\models\Campaign;
use common\models\twitch\TwitchFollow;
use \ritero\SDK\TwitchTV\TwitchSDK;
use Yii;
class ApiController extends \yii\web\Controller {

    protected $twitch;

    protected $campaign;

    public function init()
    {
        $this->enableCORS();
        
        if (\Yii::$app->request->isOptions)
        {
            \Yii::$app->end();
        }

        $this->twitch = new TwitchSDK();

        return parent::init();
    }

    public function validateRequest()
    {
        if (!(\Yii::$app->request->isGet || \Yii::$app->request->isPost))
        {
            echo 'Invalid request';
            exit;
        }

        \Yii::$app->response->format = Response::FORMAT_JSON;
        $payload = json_decode(file_get_contents('php://input'), true);

        if (isset($payload['id']) && isset($payload['key']))
        {
            $id = $payload['id'];
            $key = $payload['key'];
        }
        else
        {
            if (isset($_REQUEST['id']) && isset($_REQUEST['key']))
            {
                $id = $_REQUEST['id'];
                $key = $_REQUEST['key'];
            }
            else
            {
                throw new Exception('"id" and "key" should be set');
            }
        }


        $campaign = Campaign::findOne($id);

//        if($campaign->user->getPlan() == Plan::PLAN_PRO)
//        {
//            $this->enableCORS();
//        }

        if (!$campaign)
        {
            throw new Exception("Invalid event id");
        }

        if ($campaign->key != $key)
        {
            throw new ForbiddenHttpException('Invalid event key');
        }

        return $campaign;
    }

    public function actionCampaign() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $campaign = $this->validateRequest();
        
        $campaignArray = $campaign->toArray();
        $campaignArray['donationUrl'] = $campaign->user->getUrl() . $campaign->alias;
        
        if ( false == \Yii::$app->user->isGuest ){
            $date = (new \DateTime())->setTimezone(new \DateTimeZone(\Yii::$app->user->identity->getTimezone()));
            $campaignArray['startDateFormatted'] = $campaignArray['startDate'] ? $date->setTimestamp($campaignArray['startDate'])->format('F j, Y') : null;
            $campaignArray['endDateFormatted'] = $campaignArray['endDate'] ? $date->setTimestamp($campaignArray['endDate'])->format('F j, Y') : null;
        } else {
            $date = new \DateTime();
            $campaignArray['startDateFormatted'] = $campaignArray['startDate'] ? $date->setTimestamp($campaignArray['startDate'])->format('F j, Y') : null;
            $campaignArray['endDateFormatted'] = $campaignArray['endDate'] ? $date->setTimestamp($campaignArray['endDate'])->format('F j, Y') : null;
        }

        $campaignArray['backgroundImg'] = BaseImage::getOriginalUrlById($campaign->backgroundImageId);
        $campaignArray['goalAmountFormatted'] = '$'.\common\components\PullrUtils::formatNumber($campaign['goalAmount'], 2);
        $campaignArray['amountRaisedFormatted'] = '$'.\common\components\PullrUtils::formatNumber($campaign['amountRaised'], 2);
        $campaignArray['percentageOfGoal'] = $campaign['goalAmount'] > 0 ? round($campaign['amountRaised'] / $campaign['goalAmount'] * 100) : 0;
        if (($campaign->donationDestination == Campaign::DONATION_PARTNERED_CHARITIES) && ($campaign->type === Campaign::TYPE_CHARITY_FUNDRAISER) && $campaign->charity) {
            $campaignArray['charity'] = $campaign->charity->toArray();
        } else {
            $campaignArray['charity'] = null;
        }

        return $campaignArray;
    }

    public function actionIndex() {
        $this->validateRequest();
    }

    public function actionTeam() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $campaign = $this->validateRequest();
        if ($campaign->layoutType != Campaign::LAYOUT_TYPE_TEAM || !$campaign->channelTeam) {
            return [];
        }
        $team = $this->twitch->teamGet($campaign->channelTeam);        
        return $team;
    }

    public function actionChannels() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $campaign = $this->validateRequest();
        $result = [];
        switch ($campaign->layoutType) {
            case Campaign::LAYOUT_TYPE_SINGLE:
                $result = $this->actionSingleChannel();
            break;    
            case Campaign::LAYOUT_TYPE_TEAM:
                $result = $this->actionTeamChannel();
            break;
            case Campaign::LAYOUT_TYPE_MULTI:
                $result = $this->actionMultiChannels();
            break;
        }
        return $result;
    }

    public function actionMultiChannels() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $campaign = $this->validateRequest();
        if ($campaign->layoutType != Campaign::LAYOUT_TYPE_MULTI) {
            return [];
        }
        $channels = $campaign->getTeams()->all();
        $members = [];
        
        foreach ($channels as $channelModel) {
            $channel = $this->twitch->channelGet($channelModel->name);
            $members[] = $channel;
        }
        return $members;
    }

    public function actionTeamChannel() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $campaign = $this->validateRequest();

        if ($campaign->layoutType != Campaign::LAYOUT_TYPE_TEAM || !$campaign->channelTeam) {
            return [];
        }

        $membersList = $this->twitch->teamMembersAll($campaign->channelTeam);
        
        $offlines = [];
        $onlines = [];
        foreach ($membersList as $member) {
            if ($member->channel->status == 'offline') {
                $offlines[] = $member->channel;                
            } else {
                $onlines[] = $member->channel;
            }
        }
        /**
         * let's shuffle onlines list
         */
        shuffle($onlines);
        $members = array_merge($onlines, $offlines);
        return $members;
    }

    public function actionSingleChannel() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $campaign = $this->validateRequest();
        if ($campaign->layoutType != Campaign::LAYOUT_TYPE_SINGLE || !$campaign->channelName) {
            return [];
        }
        $channel = $this->twitch->channelGet($campaign->channelName);
        return $channel;
    }

    public function actionJs() {
        echo $this->renderFile('@frontend/views/api/api.js');
    }

    public function actionScript() {
        echo $this->renderFile('@frontend/views/api/script.js');
    }

    public function actionCampaignmultistreamlayout() {
        echo $this->renderFile('@frontend/views/api/templates/campaign/campaignMultiStreamLayout.html');
    }

    public function actionCampaignsinglestreamlayout() {
        echo $this->renderFile('@frontend/views/api/templates/campaign/campaignSingleStreamLayout.html');
    }

    public function actionCampaignteamstreamlayout() {
        echo $this->renderFile('@frontend/views/api/templates/campaign/campaignTeamStreamLayout.html');
    }

    protected function enableCORS(){
        \Yii::$app->response->headers->set('Access-Control-Allow-Origin', '*');
        \Yii::$app->response->headers->set('Access-Control-Allow-Methods', 'GET, PUT, POST, DELETE, OPTIONS');
        \Yii::$app->response->headers->set('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept');
    }
}
