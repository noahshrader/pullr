<?php
namespace backend\controllers;

use common\models\Campaign;

class CampaignController extends BackendController
{
	public function actionIndex()
	{   
            $status = isset($_POST['status']) ? $_POST['status'] : Campaign::STATUS_ACTIVE;
            $params = [];
            $params['campaigns'] = Campaign::findAll(['status' => $status]);
            $params['status'] = $status;
            return $this->render('index', $params);
	}
        
        public function actionEdit($id){
            $campaign = Campaign::findOne($id);
            if (!$campaign) throw new \yii\web\NotFoundHttpException();
            $params = [];
            $params['campaign'] = $campaign;
            return $this->render('edit',$params);
        }
        
        public function actionDelete($id){
            $campaign = Campaign::findOne($id);
            if (!$campaign) throw new \yii\web\NotFoundHttpException();
            $campaign->status = Campaign::STATUS_DELETED;
            $campaign->save();
            $this->redirect('campaign');
        }
}
