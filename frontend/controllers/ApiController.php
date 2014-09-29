<?php

namespace frontend\controllers;

use yii\web\Response;
use yii\base\Exception;
use yii\web\ForbiddenHttpException;
use common\models\Campaign;
use \ritero\SDK\TwitchTV\TwitchSDK;

class ApiController extends \yii\web\Controller {

    protected $twitch;

    protected $campaign;

    public function init() {
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        $this->twitch = new TwitchSDK();
        return parent::init();
    }

    public function validateRequest() {

        if( ! \Yii::$app->request->isPost){
            echo 'invalid post';
            exit;
        }

        \Yii::$app->response->format = Response::FORMAT_JSON;
        $payload = file_get_contents('php://input');
        $payload = json_decode($payload,true);
        if (!isset($payload['id']) || (!isset($payload['key']))) {
            throw new Exception('"id" and "key" should be set');
        }
        $id = $payload['id'];
        $key = $payload['key'];
        $campaign = Campaign::findOne($id);
        if (!$campaign) {
            throw new Exception("Invalid event id");
        }
        if ($campaign->key != $key) {
            throw new ForbiddenHttpException('Invalid event key');
        }

        return $campaign;
    }

    public function actionCampaign() {
        $campaign = $this->validateRequest();
        
        $campaignArray = $campaign->toArray();
        $campaignArray['donationUrl'] = $campaign->user->getUrl() . $campaign->alias;
        $campaignArray['startDateFormatted'] = $campaignArray['startDate'];
        $campaignArray['endDateFormatted'] = $campaignArray['endDate'];
        //$date = (new \DateTime())->setTimezone(new \DateTimeZone(\Yii::$app->user->identity->getTimezone()));
        // $campaignArray['startDateFormatted'] = $campaignArray['startDate'] ? $date->setTimestamp($campaignArray['startDate'])->format('F j, Y') : null;
        // $campaignArray['endDateFormatted'] = $campaignArray['endDate'] ? $date->setTimestamp($campaignArray['endDate'])->format('F j, Y') : null;
        $campaignArray['goalAmountFormatted'] = '$'.number_format($campaign['goalAmount']);
        $campaignArray['amountRaisedFormatted'] = '$'.number_format($campaign['amountRaised']);
        $campaignArray['percentageOfGoal'] = round($this->campaign['amountRaised'] / $campaign['goalAmount'] * 100);
        if (($campaign->donationDestination == Campaign::DONATION_PREAPPROVED_CHARITIES) && ($campaign->type === Campaign::TYPE_CHARITY_FUNDRAISER) && $campaign->charity) {
            $campaignArray['charity'] = $campaign->charity->toArray();
        } else {
            $campaignArray['charity'] = null;
        }

        echo json_encode($campaignArray);
    }

    public function actionIndex() {
        $this->validateRequest();
    }

    public function actionChannels() {
        $campaign = $this->validateRequest();

        switch ($campaign->layoutType) {
            case Campaign::LAYOUT_TYPE_SINGLE:
                $this->actionSingleChannel();
            break;    
            case Campaign::LAYOUT_TYPE_TEAM:
                $this->actionTeamChannel();
            break;
            case Campaign::LAYOUT_TYPE_MULTI:
                $this->actionMultiChannels();
            break;
        }
    }

    public function actionMultiChannels() {
        $campaign = $this->validateRequest();
        if ($campaign->layoutType != Campaign::LAYOUT_TYPE_MULTI) {
            echo json_encode([]);
            return;
        }
        $channels = $campaign->getTeams()->all();
        $members = [];
        
        foreach ($channels as $channelModel) {
            $channel = $this->twitch->channelGet($channelModel->name);
            $members[] = $channel;
        }
        echo json_encode($members);
    }

    public function actionTeamChannel() {
        $campaign = $this->validateRequest();

        if ($campaign->layoutType != Campaign::LAYOUT_TYPE_TEAM || !$campaign->channelTeam) {
            echo json_encode([]);
            return;
        }

        $membersList = $this->twitch->teamMembersAll($campaign->channelTeam);

        $offlines = [];
        $onlines = [];
        foreach ($membersList as $member) {
            if ($member->channel->status == 'live') {
                $onlines[] = $member->channel;
            } else {
                $offlines[] = $member->channel;
            }
        }
        /**
         * let's shuffle onlines list
         */
        shuffle($onlines);
        $members = array_merge($onlines, $offlines);
        echo json_encode($members);
    }

    public function actionSingleChannel() {
        $campaign = $this->validateRequest();
        if ($campaign->layoutType != Campaign::LAYOUT_TYPE_SINGLE || !$campaign->channelName) {
            echo json_encode([]);
            return;
        }
        $channel = $this->twitch->channelGet($campaign->channelName);
        echo json_encode($channel);
    }

    public function actionJs() {
        echo $this->renderFile('@frontend/views/api/api.js');
        \common\components\Application::frontendUrl('/');
    }

    public function actionCampaignmultistreamlayout() {
        echo $this->renderFile('@frontend/views/api/templates/campaign/campaignMultiStreamLayout.html');
        \common\components\Application::frontendUrl('/');
    }

    public function actionCampaignsinglestreamlayout(){
        echo $this->renderFile('@frontend/views/api/templates/campaign/campaignSingleStreamLayout.html');
        \common\components\Application::frontendUrl('/');   
    }

    public function actionCampaignteamstreamlayout(){
        echo $this->renderFile('@frontend/views/api/templates/campaign/campaignTeamStreamLayout.html');
        \common\components\Application::frontendUrl('/');   
    }

}
