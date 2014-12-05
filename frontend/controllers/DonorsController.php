<?php

namespace frontend\controllers;

use common\models\FirstGiving;
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
use common\models\Charity;
use common\models\User;
use common\models\CampaignInvite;
use common\models\mail\Mail;
use common\models\Donation;
use common\components\PullrUtils;
use HttpRequest;

class DonorsController extends FrontendController {

    public function actionIndex($viewDonorParams = null){
        $selectedEmail = $viewDonorParams ? $viewDonorParams['email'] : null;
        $user = \Yii::$app->user->identity;
        $connection = \Yii::$app->db;
        $sql = 'SELECT email, SUM(amount) sum, GROUP_CONCAT(DISTINCT nameFromForm SEPARATOR " ") nameFromForm, firstName, lastName FROM '.Donation::tableName().
                ' WHERE (campaignUserId = '.$user->id.' or parentCampaignUserId = '. $user->id.')'.
                ' AND paymentDate > 0 AND email<>"" GROUP BY email ORDER BY sum DESC, lastName ASC, firstName ASC';
        $command = $connection->createCommand($sql);
        $donors = $command->queryAll();

        if ( (sizeof($donors) > 0) && ($viewDonorParams == null)){
            /*let's auto select first donor*/
            return $this->actionView($donors[0]['email']);
        }
        foreach ($donors as &$donor){
            $lastName = $donor['lastName'];
            $firstName = $donor['firstName'];
            if ($lastName && $firstName){
                $donor['name'] = $firstName.' '.$lastName;
            } else if ($lastName){
                $donor['name'] = $lastName;
            } else if ($firstName){
                $donor['name'] = $firstName;
            } else {
                $donor['name'] = $donor['nameFromForm'] ? $donor['nameFromForm'] : Donation::ANONYMOUS_NAME;
            }
            
            $donor['sum'] = '$'.PullrUtils::formatNumber($donor['sum'], 2);
            $donor['href'] = 'app/donors/view?email='.urlencode($donor['email']);
            $donor['isActive'] = $selectedEmail && ($donor['email'] == $selectedEmail);
        }
        
        return $this->render('donors',[
            'donors' => $donors,
            'viewDonorParams' => $viewDonorParams
        ]);
    }

    /*viewing single donor*/
    public function actionView($email){
        $user = \Yii::$app->user->identity;
        $donationsQuery = Donation::findByEmailAndUserId($email, $user->id);
        $donations = $donationsQuery->all();
        if (sizeof($donations) == 0){
            throw new NotFoundHttpException('Donations for such email don\'t exist');
        }

        $viewDonorParams = [];
        $viewDonorParams['donations'] = $donations;
        $viewDonorParams['email'] = $email;
        $lastName = $donations[0]->lastName;
        $firstName = $donations[0]->firstName;
        if ($lastName && $firstName){
            $name = $firstName.' '.$lastName;
        } else if ($lastName){
            $name = $lastName;
        } else if ($firstName){
            $name = $firstName;
        } else {
            $name = $donations[0]['nameFromForm'] ? $donations[0]['nameFromForm'] : Donation::ANONYMOUS_NAME;
        }

        $viewDonorParams['name'] = $name;
        $viewDonorParams['totalDonated'] = $donationsQuery->sum('amount');
        $viewDonorParams['topDonation'] = $donationsQuery->max('amount');
        return $this->actionIndex($viewDonorParams);
    }
}
