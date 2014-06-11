<?php

namespace frontend\controllers;

use yii\web\Response;
use yii\base\Exception;
use yii\web\ForbiddenHttpException;
use common\models\Campaign;
use \ritero\SDK\TwitchTV\TwitchSDK;

class ApiController extends \yii\web\Controller {

    public function validateRequest() {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        if (!isset($_REQUEST['id']) || (!isset($_REQUEST['key']))) {
            throw new Exception('"id" and "key" should be set');
        }
        $id = $_REQUEST['id'];
        $key = $_REQUEST['key'];
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
        $campaignArray['startDateFormatted'] = $campaignArray['startDate'] ? date('F j, Y', $campaignArray['startDate']) : null;
        $campaignArray['endDateFormatted'] = $campaignArray['endDate'] ? date('F j, Y', $campaignArray['endDate']) : null;
        if ($campaign->donationDestination == Campaign::DONATION_PREAPPROVED_CHARITIES &&
                in_array($campaign->type, [Campaign::TYPE_CHARITY_EVENT, Campaign::TYPE_CHARITY_FUNDRAISER]) && $campaign->charity) {
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
        if ($campaign->layoutType != Campaign::LAYOUT_TYPE_TEAM || !$campaign->channelTeam) {
            echo json_encode([]);
            return;
        }

        $twitch = new TwitchSDK();
        $membersList = $twitch->teamMembersAll($campaign->channelTeam);

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

    public function actionJs() {
        echo $this->renderFile('@frontend/views/api/api.js');
        \common\components\Application::frontendUrl('/');

//        echo $this->renderPartial('api.js')
    }

}
