<?php

namespace frontend\controllers;

use frontend\controllers\FrontendController;
use common\models\Campaign;
use yii\web\NotFoundHttpException;
use common\components\Application;
use common\models\LayoutTeam;
use yii\web\Response;
use Yii;
use kartik\widgets\ActiveForm;
use common\components\UploadImage;
use common\models\Theme;
use common\models\Plan;

class CampaignController extends FrontendController {

    public function actionAdd() {
        $campaign = new Campaign();
        return $this->actionIndex($campaign);
    }

    public function actionEdit() {
        $id = $_GET['id'];
        $campaign = Campaign::findOne($id);

        if (!$campaign) {
            throw new NotFoundHttpException('Layout not found');
        }
        if ($campaign->userId != \Yii::$app->user->id && !Application::IsAdmin()) {
            throw new \yii\web\ForbiddenHttpException();
        }


        return $this->actionIndex($campaign);
    }

    public function actionRemove() {
        $id = $_POST['id'];
        $campaign = Campaign::findOne($id);

        if (!$campaign) {
            throw new NotFoundHttpException('Layout not found');
        }
        if ($campaign->userId != \Yii::$app->user->id && !Application::IsAdmin()) {
            throw new \yii\web\ForbiddenHttpException();
        }

        $campaign->status = Campaign::STATUS_DELETED;
        $campaign->save();
        $this->redirect('app/campaign');
    }

    public function actionIndex(Campaign $campaign = null) {
        $isNewRecord = $campaign && $campaign->isNewRecord;
        if ($campaign && $campaign->load($_POST) && $campaign->save()) {
            /**from html5 datetime-local tag to timestamp */
            if ($campaign->startDate && !is_numeric($campaign->startDate)){
                $campaign->startDate =(new \DateTime($campaign->startDate))->getTimestamp();
            }
            if ($campaign->endDate && !is_numeric($campaign->endDate)){
                $campaign->endDate =(new \DateTime($campaign->endDate))->getTimestamp();
            }
            
            UploadImage::UploadLogo($campaign);

            if ($isNewRecord) {
                $this->redirect('app/campaign/edit?id=' . $campaign->id);
            }
        }
       
        if ($campaign){
            if (!$campaign->startDate){
                $campaign->startDate = time();
            }
            if (!$campaign->endDate){
                $campaign->endDate = time()+60*60*24*4;
            }
            if (is_numeric($campaign->startDate)){
                $campaign->startDate = strftime('%Y-%m-%dT%H:%M:%S', $campaign->startDate);
            }
            if (is_numeric($campaign->endDate)){
                $campaign->endDate = strftime('%Y-%m-%dT%H:%M:%S', $campaign->endDate);
            }
        }
        
        $user = \Yii::$app->user->identity;
        $params = [];
        $params['selectedCampaign'] = $campaign;
        $params['campaigns'] = $user->campaigns;
        
        /**from timestamp to html5 datetime-local tag*/ 
        
        return $this->render('index', $params);
    }

    public function actionLayoutteams() {
        $id = $_REQUEST['id'];
        $teams = LayoutTeam::find()->where(['campaignId' => $id])->orderBy('date DESC')->all();

        $teamsOut = [];
        foreach ($teams as $team) {
            $teamsOut[] = $team->toArray();
        }

        return json_encode($teamsOut);
    }

    public function actionLayoutteamedit() {
        $id = $_REQUEST['id'];
        $layoutTeam = LayoutTeam::findOne($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_REQUEST['get']) && !isset($_REQUEST['save'])) {
            $layoutTeam->load($_POST);
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($layoutTeam);
        }
        if ($layoutTeam->load($_POST) && $layoutTeam->save($_POST)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($layoutTeam);
        }
        $layout = Campaign::findOne($layoutTeam->layoutId);
        if ($layout->userId != \Yii::$app->user->id && !Application::IsAdmin()) {
            throw new \yii\web\ForbiddenHttpException();
        }
        return $this->renderAjax('layout-team-edit', ['layoutTeam' => $layoutTeam]);
    }

    public function actionLayoutteamremove() {
        $id = $_POST['id'];
        $name = $_POST['name'];

        $campaign = Campaign::findOne($id);

        if (!$campaign) {
            throw new NotFoundHttpException('Layout not found');
        }

        if ($campaign->userId != \Yii::$app->user->id && !Application::IsAdmin()) {
            throw new \yii\web\ForbiddenHttpException();
        }

        $campaignTeam = LayoutTeam::find()->where(['name' => $name, 'campaignId' => $id])->one();

        if ($campaignTeam) {
            $campaignTeam->delete();
        }
    }

    public function actionLayoutteamadd() {
        $id = $_POST['id'];
        $name = $_POST['name'];

        $campaign = Campaign::findOne($id);

        if (!$campaign) {
            throw new NotFoundHttpException('Layout not found');
        }

        if ($campaign->userId != \Yii::$app->user->id && !Application::IsAdmin()) {
            throw new \yii\web\ForbiddenHttpException();
        }


        $layoutTeam = new LayoutTeam();

        $layoutTeam->layoutId = $id;
        $layoutTeam->name = $name;

        $layoutTeam->save();
    }

    public function actionModalthemes() {
        $type = $_POST['type'];
        $plan = \Yii::$app->user->identity->getPlan();

        $themesQuery = Theme::find()->where(['status' => Theme::STATUS_ACTIVE]);
        if ($plan == Plan::PLAN_BASE) {
            $themesQuery->andWhere(['plan' => Plan::PLAN_BASE]);
        }
        if ($type){
            $themesQuery->andWhere(['layoutType' => $type]);
        }

        $themes = $themesQuery->all();
        return $this->renderPartial('modalThemes',[
            'themes' => $themes, 'type' => $type
        ]);
    }

}
