<?php

namespace frontend\controllers;

use Yii;
use yii\web\Response;


class StreamboardController extends \yii\web\Controller {

    public function actionIndex() {
        if (Yii::$app->user->isGuest) {
            return Yii::$app->user->loginRequired();
        }

        $user = Yii::$app->user->identity;

        $this->layout = 'streamboard';



        $userId = \Yii::$app->user->id;
        $sql="SELECT * FROM tbl_campaign WHERE userId = '{$userId}'";
        $campaigns=Yii::$app->db->createCommand($sql)->queryAll();
//        var_dump($campaigns);exit;

        return $this->render('index', [
            'user' => $user,
            'campaigns' => $campaigns,
            'csrf_token_name' => Yii::$app->request->csrfParam,
            'csrf_token' => Yii::$app->request->getCsrfToken(),
        ]);
    }

    public function actionAdd_donation_ajax() {

    }

    public function actionGet_donations_ajax() {
        if (Yii::$app->user->isGuest
//            || !Yii::$app->request->isAjax
        ) {
            return false;
        }

        $launch_time = (int) $_POST['streamboard_launch_time'];
        var_dump($_POST['campaigns']);exit;

        $userId = \Yii::$app->user->id;

        $sql="SELECT * FROM tbl_donation WHERE userId = '{$userId}'";
        $donations=Yii::$app->db->createCommand($sql)->queryAll();


        $donations = array(
            array('name'=>'Andrey', 'amount' =>'100'),
            array('name'=>'Andrey2', 'amount' =>'140'),
        );
        if(rand(0,2) > 1) {
            $donations[] = array('name'=>'Random', 'amount' =>'400');
        }
        echo json_encode(array('donations'=>$donations));
    }

}
