<?php

namespace backend\controllers;

use common\models\Charity;
use yii\web\NotFoundHttpException;

class CharityController extends BackendController {

    public function actionIndex() {
        $status = isset($_POST['status']) ? $_POST['status'] : Charity::STATUS_ACTIVE;
        $params = [];
        $params['charities'] = Charity::find()->where(['status' => $status])->all();
        $params['status'] = $status;
        return $this->render('index', $params);
    }

    public function actionAdd(){
        return $this->actionEdit();
    }
    public function actionEdit($id = 0) {
        $charity = ($id == 0) ? new Charity() : Charity::find($id);
        
        if (!$charity) {
            throw new NotFoundHttpException("Charity with id = $id is not found");
        }

        if ($charity->load($_POST) && $charity->save()) {
            if ($id == 0) {
                /* so new city has been added */
                $this->redirect('charity/edit?id=' . $charity->id);
            }
        }

        return $this->render('edit', ['charity' => $charity]);
    }

}
