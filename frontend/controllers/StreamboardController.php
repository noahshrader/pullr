<?php

namespace frontend\controllers;

use Yii;
use yii\web\Response;


class StreamboardController extends \yii\web\Controller
{

    public function actionIndex() {
        if (Yii::$app->user->isGuest) {
            return Yii::$app->user->loginRequired();
        }

        $user = Yii::$app->user->identity;

        $this->layout = 'streamboard';


        $userId = \Yii::$app->user->id;
        $sql = "SELECT * FROM tbl_campaign WHERE userId = '{$userId}'";
        $campaigns = Yii::$app->db->createCommand($sql)->queryAll();
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

        $streamboard_launch_time = (int)$_POST['streamboard_launch_time'];
        $selected_campaigns = $_POST['selected_campaigns'];
        foreach($selected_campaigns as $key => $value) {
            $selected_campaigns[$key] = (int) $value;
        }

        //        var_dump($_POST['campaigns']);exit;

        $userId = \Yii::$app->user->id;

        $stats = array(
            'number_of_donations' => 0,
            'total_amount' => 0,
            'number_of_donors' => 0,
            'top_donation_amount' => 0,
            'top_donation_name' => 0,
            'top_donors' => array(),
        );
        $donors = array();

        $campaigns_filter = implode(',', $selected_campaigns);

        $sql = "
	            SELECT *
	            FROM tbl_donation
	            WHERE userId = '{$userId}'
	              AND createdDate > '{$streamboard_launch_time}'
	              AND campaignId IN ($campaigns_filter)
	            LIMIT 1
	          ";
        $donations = Yii::$app->db->createCommand($sql)->queryAll();
        if ($donations) {
            $stats['number_of_donations'] = count($donations);
            foreach($donations as $donation) {
                $stats['total_amount'] = $stats['total_amount'] + $donation['amount'];
                if (!empty($donors[$donation['name']])) {
                    $donors[$donation['name']] = $donors[$donation['name']] + $donation['amount'];
                } else {
                    $donors[$donation['name']] = $donation['amount'];
                }

                if ($donation['amount'] > $stats['top_donation_amount']) {
                    $stats['top_donation_amount'] = $donation['amount'];
                    $stats['top_donation_name'] = $donation['name'];
                }
            }

            arsort($donors);
            $stats['top_donors'] = array_slice($array, 0, 3);
        }
        $stats['number_of_donors'] = count($donors);

        //		$donations = array(
        //			array('name' => 'Andrey', 'amount' => '100'),
        //			array('name' => 'Andrey2', 'amount' => '140'),
        //		);
        //		if (rand(0, 2) > 1) {
        //			$donations[] = array('name' => 'Random', 'amount' => '400');
        //		}

        echo json_encode(array(
            'donation' => $donations,
            'stats' => $stats
        ));
    }

}
