<?php

namespace frontend\controllers;

use frontend\controllers\FrontendController;
use common\models\Layout;
use yii\web\NotFoundHttpException;
use common\components\Application;
use common\models\LayoutTeam;
use yii\web\Response;
use Yii;
use kartik\widgets\ActiveForm;
use common\models\base\BaseImage;
use common\components\UploadImage;

class PullrLayoutController extends FrontendController {

    public function actionAdd() {
        $layout = new Layout();
        return $this->actionIndex($layout);
    }

    public function actionEdit() {
        $id = $_GET['id'];
        $layout = Layout::find($id);

        if (!$layout) {
            throw new NotFoundHttpException('Layout not found');
        }
        if ($layout->userId != \Yii::$app->user->id && !Application::IsAdmin()) {
            throw new \yii\web\ForbiddenHttpException();
        }


        return $this->actionIndex($layout);
    }

    public function actionRemove() {
        $id = $_POST['id'];
        $layout = Layout::find($id);

        if (!$layout) {
            throw new NotFoundHttpException('Layout not found');
        }
        if ($layout->userId != \Yii::$app->user->id && !Application::IsAdmin()) {
            throw new \yii\web\ForbiddenHttpException();
        }

        $layout->status = Layout::STATUS_DELETED;
        $layout->save();
        $this->redirect('pullrlayout');
    }

    public function actionIndex($layout = null) {
        $isNewRecord = $layout && $layout->isNewRecord;
        if ($layout && $layout->load($_POST) && $layout->save()) {
            UploadImage::UploadLogo($layout);

            if ($isNewRecord) {
                $this->redirect('pullrlayout/edit?id=' . $layout->id);
            }
        }
        $user = \Yii::$app->user->identity;
        $params = [];
        $params['selectedLayout'] = $layout;
        $params['layouts'] = $user->layouts;
        return $this->render('index', $params);
    }

    public function actionLayoutteams() {
        $id = $_REQUEST['id'];
        $teams = LayoutTeam::find()->where(['layoutId' => $id])->orderBy('date DESC')->all();

        $teamsOut = [];
        foreach ($teams as $team) {
            $teamsOut[] = $team->toArray();
        }

        return json_encode($teamsOut);
    }

    public function actionLayoutteamedit() {
        $id = $_REQUEST['id'];
        $layoutTeam = LayoutTeam::find($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_REQUEST['get']) && !isset($_REQUEST['save'])) {
            $layoutTeam->load($_POST);
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($layoutTeam);
        }
        if ($layoutTeam->load($_POST) && $layoutTeam->save($_POST)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($layoutTeam);
        }
        $layout = Layout::find($layoutTeam->layoutId);
        if ($layout->userId != \Yii::$app->user->id && !Application::IsAdmin()) {
            throw new \yii\web\ForbiddenHttpException();
        }
        return $this->renderAjax('layout-team-edit', ['layoutTeam' => $layoutTeam]);
    }

    public function actionLayoutteamremove() {
        $id = $_POST['id'];
        $name = $_POST['name'];

        $layout = Layout::find($id);

        if (!$layout) {
            throw new NotFoundHttpException('Layout not found');
        }

        if ($layout->userId != \Yii::$app->user->id && !Application::IsAdmin()) {
            throw new \yii\web\ForbiddenHttpException();
        }

        $layoutTeam = LayoutTeam::find()->where(['name' => $name, 'layoutId' => $id])->one();

        if ($layoutTeam) {
            $layoutTeam->delete();
        }
    }

    public function actionLayoutteamadd() {
        $id = $_POST['id'];
        $name = $_POST['name'];

        $layout = Layout::find($id);

        if (!$layout) {
            throw new NotFoundHttpException('Layout not found');
        }

        if ($layout->userId != \Yii::$app->user->id && !Application::IsAdmin()) {
            throw new \yii\web\ForbiddenHttpException();
        }


        $layoutTeam = new LayoutTeam();

        $layoutTeam->layoutId = $id;
        $layoutTeam->name = $name;

        $layoutTeam->save();
    }

}
