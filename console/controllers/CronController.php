<?php

namespace console\controllers;

use yii\console\Controller;
use frontend\models\site\DeactivateAccount;
use common\models\User;

class CronController extends Controller {
    /**
     * this action deactive users which left deactivation request and were not online more than 30 days
     */
    public function actionUserdelete(){
        $timeLimit = time()-DeactivateAccount::DEACTIVATION_PERIOD;
        $deactivations = DeactivateAccount::find()->where(['processingDate' => null])->andWhere('creationDate < '.$timeLimit)->all();
        foreach ($deactivations as $deactivation){
            $user = $deactivation->user;
            if ($user->last_login <= $deactivation->creationDate+3){
                $user->name.' deleted';
                $user->setScenario('status');
                $user->status = User::STATUS_DELETED;
                $user->save();
            } 
            $deactivation->setScenario('processing');
            $deactivation->processingDate = time();
            $deactivation->save();
        }
    }
}
