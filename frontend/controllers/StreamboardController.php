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
        $userId = \Yii::$app->user->id;

        $amount = rand(100, 10000);
        $name = 'rand_user_'.intval(rand(1,3));
        $sql = "
	        INSERT INTO tbl_donation
	        SET
	          userId = '{$userId}'
	          ,campaignId = 5
	          ,createdDate = UNIX_TIMESTAMP()
	          ,amount = '{$amount}'
	          ,`name` = '{$name}'
	          ,comments = 'Some test comment here.Some test comment here.'
	    ";
        $res = Yii::$app->db->createCommand($sql)->execute();
    }

    public function actionGet_donations_ajax() {
        if (Yii::$app->user->isGuest
//            || !Yii::$app->request->isAjax
        ) {
            return;
        }

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

        $streamboard_launch_time = (int)$_POST['streamboard_launch_time'];
        $selected_campaigns = array();
        if (!empty($_POST['selected_campaigns'])) {
            $selected_campaigns = $_POST['selected_campaigns'];
        }
        if (empty($selected_campaigns)) {
            echo json_encode(array(
                'donations' => array(),
                'stats' => $stats
            ));
            return;
        }

        foreach($selected_campaigns as $key => $value) {
            $selected_campaigns[$key] = (int)$value;
        }

        //        var_dump($_POST['campaigns']);exit;

        $campaigns_filter = implode(',', $selected_campaigns);

        $sql = "
	            SELECT d.name, d.amount
	              , LOWER(FROM_UNIXTIME(d.createdDate,'%d/%m/%Y %l:%i %p'))
	                  as date_formatted
	              , d.comments, c.name as campaign_name
	            FROM tbl_donation d
	            INNER JOIN tbl_campaign c ON d.campaignId = c.id
	            WHERE d.userId = '{$userId}'
	              AND d.createdDate > '{$streamboard_launch_time}'
	              AND d.campaignId IN ($campaigns_filter)
	            ORDER BY d.createdDate DESC
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
            foreach(array_slice($donors, 0, 3) as $name => $amount) {
                $stats['top_donors'][] = array(
                    'name' => $name,
                    'amount' => $amount,
                );
            }

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
            'donations' => $donations,
            'stats' => $stats
        ));
    }

}
