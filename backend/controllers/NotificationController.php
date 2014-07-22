<?php
namespace backend\controllers;

use common\models\notifications\SystemNotification;

class NotificationController extends BackendController{

    /**
     * route /notification
    */
    public function actionIndex() {
        $params = [];
        $params['notifications'] = SystemNotification::find()->all();
        return $this->render('index', $params);
    }

    /**
     * route /notification/add
     */
    public function actionAdd(){
        return $this->actionEdit();
    }

    /**
     * route /notification/edit?id=<id>
     */
    public function actionEdit($id = 0) {
        $notice = ($id == 0) ? new SystemNotification() : SystemNotification::findOne($id);

        if (!$notice) {
            throw new NotFoundHttpException("Notification with id = $id is not found");
        }

        if ($notice->load($_POST)) {

            if ($notice->date && !is_numeric($notice->date)) {
                $notice->date = (new \DateTime($notice->date))->getTimestamp();
            }

            if ($notice->save()) {
                $this->redirect('notification/edit?id=' . $notice->id);
            }
        }


        if ($notice->id) {
            if (is_numeric($notice->date)) {
                $notice->date = strftime('%Y-%m-%dT%H:%M', $notice->date);
            }
        }

        return $this->render('edit', ['notice' => $notice]);
    }
}