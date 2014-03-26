<?php

namespace frontend\controllers;

use yii\web\Controller;
use common\models\Layout;
use yii\web\NotFoundHttpException;
use common\components\Application;
use common\models\LayoutTeam;


class PullrLayoutController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => \yii\web\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionAdd(){
        $layout = new Layout();
        return $this->actionIndex($layout);
    }
    
    public function actionEdit(){
        $id = $_GET['id'];
        $layout = Layout::find($id);
        
        if (!$layout){
            throw new NotFoundHttpException('Layout not found');
        }
        if ($layout->userId != \Yii::$app->user->id  && !Application::IsAdmin()){
            throw new \yii\web\ForbiddenHttpException();
        }
        return $this->actionIndex($layout);
    }
    
    public function actionRemove(){
        $id = $_POST['id'];
        $layout = Layout::find($id);
        
        if (!$layout){
            throw new NotFoundHttpException('Layout not found');
        }
        if ($layout->userId != \Yii::$app->user->id  && !Application::IsAdmin()){
            throw new \yii\web\ForbiddenHttpException();
        }
        
        $layout->status = Layout::STATUS_DELETED;
        $layout->save();
        $this->redirect('pullrlayout');
    }
    public function actionIndex($layout = null) {
        $isNewRecord = $layout && $layout->isNewRecord;
        if ($layout && $layout->load($_POST) && $layout->save()){
            if ($isNewRecord){
                $this->redirect('pullrlayout/edit?id='.$layout->id);
            }
        }
        $userId = \Yii::$app->user->id;
        $params = [];
        $params['selectedLayout'] = $layout;
        $params['layouts'] = Layout::find()->where(['userId' => $userId, 'status' => Layout::STATUS_ACTIVE])->orderBy('date DESC')->all();
        return $this->render('index', $params);
    }
    
    public function actionLayoutteams(){
        $id = $_REQUEST['id'];
        $teams = LayoutTeam::find()->where(['layoutId' => $id])->orderBy('date DESC')->all();

        $teamNames = [];
        foreach ($teams as $team){
            $teamNames[] = $team->name;
        }
        
        return json_encode($teamNames);
    }
    
    public function actionLayoutteamremove(){
        $id = $_POST['id'];
        $name = $_POST['name'];
        
        $layout = Layout::find($id);
        
        if (!$layout){
            throw new NotFoundHttpException('Layout not found');
        }
        
        if ($layout->userId != \Yii::$app->user->id  && !Application::IsAdmin()){
            throw new \yii\web\ForbiddenHttpException();
        }
        
        $layoutTeam = LayoutTeam::find()->where(['name' => $name, 'layoutId' => $id])->one();
        
        if ($layoutTeam){
            $layoutTeam->delete();
        }
        
    }
    
    public function actionLayoutteamadd(){
        $id = $_POST['id'];
        $name = $_POST['name'];
        
        $layout = Layout::find($id);
        
        if (!$layout){
            throw new NotFoundHttpException('Layout not found');
        }
        
        if ($layout->userId != \Yii::$app->user->id  && !Application::IsAdmin()){
            throw new \yii\web\ForbiddenHttpException();
        }
        
        
        $layoutTeam = new LayoutTeam();

        $layoutTeam->layoutId = $id;
        $layoutTeam->name = $name;
        
        $layoutTeam->save();
    }
}
