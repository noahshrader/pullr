<?php

namespace frontend\controllers;


use yii\web\Response;
use yii\base\Exception;
use yii\web\ForbiddenHttpException;
use common\models\Layout;
use \ritero\SDK\TwitchTV\TwitchSDK;

class ApiController extends \yii\web\Controller {
    public function validateRequest(){
        \Yii::$app->response->format = Response::FORMAT_JSON;
        
        if (!isset($_REQUEST['id']) || (!isset($_REQUEST['key']))){
            throw new Exception('"id" and "key" should be set');
        }
        $id = $_REQUEST['id'];
        $key = $_REQUEST['key'];
        $layout = Layout::findOne($id);
        if (!$layout){
            throw new Exception("Invalid event id");
        }
        if ($layout->key != $key){
            throw new ForbiddenHttpException('Invalid event key');
        }
        
        return $layout;
    }
    
    public function actionLayout(){
        $layout = $this->validateRequest();
        echo json_encode($layout->toArray());
    }
    
    public function actionEvent(){
        $layout = $this->validateRequest();
        $event = $layout->event;
        if (!$event){
            echo json_encode(null);
        } else {
            $eventArray = $layout->event->toArray();
            $eventArray['startDateFormatted'] = $eventArray['startDate'] ? date('F j, Y', $eventArray['startDate']) : null;
            $eventArray['endDateFormatted'] = $eventArray['endDate'] ? date('F j, Y', $eventArray['endDate']) : null;
            echo json_encode($eventArray);
        }
    }
    
    public function actionIndex(){
        $this->validateRequest();
    }
    
    public function actionChannels(){
        $layout = $this->validateRequest();
        if ($layout->type != Layout::TYPE_TEAM || !$layout->channelTeam){
            echo json_encode([]);
            return;
        }
        
        $twitch = new TwitchSDK();
        $membersList = $twitch->teamMembersAll($layout->channelTeam);
        
        $offlines = [];
        $onlines = [];
        foreach ($membersList as $member){
            if ($member->channel->status == 'live'){
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
    
    public function actionJs(){
        echo $this->renderFile('@frontend/views/api/api.js');
        \common\components\Application::frontendUrl('/');
        
//        echo $this->renderPartial('api.js')
    }
}
