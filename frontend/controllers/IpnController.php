<?php
namespace frontend\controllers;

use common\components\PullrPayment;
use common\models\Ipn;
use common\models\Payment;
use common\models\RecurringProfile;
use common\models\User;

class IpnController extends FrontendController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['notify', 'notifyadaptive'],
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }

    /**
     * Checks if received IPN-message is genuine by sending it back to PayPal server and receiving VERIFIED response
     * @param string $requestData Raw HTTP POST data
     * @return bool
     */
    private function isRequestGenuine($requestData)
    {
        $validateCmd = "{$requestData}&cmd=_notify-validate";

        $payPalHost = \Yii::$app->params['payPalHost'];
        $paypalUrl = "$payPalHost/cgi-bin/webscr";

        $ch = curl_init($paypalUrl);
        if ($ch == FALSE)
        {
            return FALSE;
        }

        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $validateCmd);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Connection: Close']);

        $res = curl_exec($ch);

        // Inspect IPN validation result and act accordingly
        if (strcmp($res, "VERIFIED") == 0)
        {
            return TRUE;
        }
        else
        {
            if (strcmp($res, "INVALID") == 0)
            {
                return FALSE;
            }
        }

        return FALSE;
    }

    /**
     * Turns raw HTTP POST data into array
     * @param $requestData
     * @return array
     */
    private function requestDataAsArray($requestData)
    {
        $rawPostArray = explode('&', $requestData);
        $myPost = array();
        foreach ($rawPostArray as $keyVal)
        {
            $keyVal = explode('=', $keyVal);
            if (count($keyVal) == 2)
            {
                $myPost[$keyVal[0]] = urldecode($keyVal[1]);
            }
        }

        return $myPost;
    }

    /**
     *  Gateway for incoming PayPal Adaptive IPN-requests
     */
    public function actionNotifyadaptive()
    {
        $requestData = file_get_contents('php://input');

        $data = $this->requestDataAsArray($requestData);

        if (!empty($data['transaction_type']))
        {
            $ipnRequest = new Ipn();
            $ipnRequest->createdDate = time();
            $ipnRequest->txnType = $data['transaction_type'];
            $ipnRequest->rawData = $requestData;
            $ipnRequest->save();
        }

        if (!empty($data['pay_key']))
        {
            $apiConfig = \Yii::$app->params['payPal'];
            (new PullrPayment($apiConfig))->finishDonationPayment($data['pay_key']);
        }
    }

    /**
     * Gateway for incoming PayPal Pro account IPN-requests
     */
    public function actionNotify()
    {
        $requestData = file_get_contents('php://input');

        if ($this->isRequestGenuine($requestData))
        {
            $data = $this->requestDataAsArray($requestData);

            // Log all incoming genuine IPN requests
            $ipnRequest = new Ipn();
            $ipnRequest->createdDate = time();
            $ipnRequest->txnType = $data['txn_type'];
            $ipnRequest->rawData = $requestData;
            $ipnRequest->save();

            switch ($data['txn_type'])
            {
                // For donation payment percent received
                case 'web_accept':
                    if (($data['payment_status'] === 'Completed') )
                    {
                        $payment = Payment::findOne(['payPalTransactionId' => $data['txn_id']]);
                        
                        if (isset($payment) && !empty($payment->relatedId) && isset($data['first_name']) && isset($data['last_name']))
                        {
                            $donation = \common\models\Donation::findOne(['id' => $payment->relatedId]);   
                            if (isset($donation))
                            {
                                $donation->firstName = $data['first_name'];
                                $donation->lastName = $data['last_name'];
                                $donation->save();
                                
                                return true;
                            }
                        }
                        
                        throw new \Exception();
                    }
                    break;

                // For recurring payment received
                case 'recurring_payment':
                    if (($data['payment_status'] === 'Completed') && (!Payment::txnAlreadyProcessed($data['txn_id'])))
                    {
                        $recurringProfile = RecurringProfile::findOne(['profileId' => $data['recurring_payment_id']]);

                        if (isset($recurringProfile))
                        {
                            $paymentParams = PullrPayment::getPaymentParamsForMoney($data['mc_gross']);

                            User::findOne($recurringProfile->userId)->prolong($data['mc_gross']);

                            $payment = new Payment();
                            $payment->status = Payment::STATUS_APPROVED;
                            $payment->userId = $recurringProfile->userId;
                            $payment->amount = $data['mc_gross'];
                            $payment->payPalTransactionId = $data['txn_id'];
                            $payment->createdDate = time();
                            $payment->paymentDate = time();
                            $payment->type = $paymentParams['paymentType'];
                            $payment->save();
                        }
                    }
                    break;

                // For recurring payment created / cancelled
                case 'recurring_payment_profile_cancel':
                case 'recurring_payment_profile_created':
                    $recurringProfile = RecurringProfile::findOne(['profileId' => $data['recurring_payment_id']]);
                    if(isset($recurringProfile))
                    {
                        $recurringProfile->status = $data['profile_status'];
                        $recurringProfile->save();
                    }
                    break;
            }
        }
    }
} 