<?php

namespace console\controllers;

use common\models\Campaign;
use yii\console\Controller;
use frontend\models\site\DeactivateAccount;
use common\models\User;

class CronController extends Controller {
    /**
     * Deactive users which left deactivation request and were not online more than 20 days
     */
    public function actionUserdelete()
    {
        $timeLimit = time() - 0;
        $deactivateRequests = DeactivateAccount::find()->where(['processingDate' => null])->andWhere('creationDate < '.$timeLimit)->all();

        foreach ($deactivateRequests as $request)
        {
            $user = $request->user;

            if ($user->last_login <= $request->creationDate+3)
            {
                $user->setScenario('status');
                $user->status = User::STATUS_DELETED;
                $user->save();

                Campaign::updateAll(['status' => Campaign::STATUS_DELETED], 'userId = :userId', [':userId' => $user->id]);
            }

            $request->setScenario('processing');
            $request->processingDate = time();
            $request->save();
        }
    }
}
