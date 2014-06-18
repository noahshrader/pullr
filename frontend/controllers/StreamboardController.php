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
//        var_dump($user);exit;

        $this->layout = 'streamboard';

        return $this->render('index', [
                    'user' => $user,
        ]);

    }

}
