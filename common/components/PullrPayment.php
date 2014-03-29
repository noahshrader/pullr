<?php

namespace common\components;

use PayPal\Rest\ApiContext;
use PayPal\Api\Payment;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\Payer;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\RedirectUrls;
use common\models\Plan;
use PayPal\Api\PaymentExecution;

class PullrPayment extends \yii\base\Component {

    public $clientId = 'AdhS6hAM2klW0zvrByqMTUAwosKCt8kMrhUPN6-HHzoCaJscFJHsGfGUvLzP';
    public $clientSecret = 'EHYMZhAE29WHfY8T37s-j-2wMOL8SMjjB3uX-9h9uz2snme0pL_tKYLg3YK4';

    public $apiContext;
    
    public function __construct($config = array()) {
        parent::__construct($config);
        define('PP_CONFIG_PATH', '/var/www/pullr/common/config/paypal');
        $this->apiContext = new ApiContext(new OAuthTokenCredential($this->clientId, $this->clientSecret)); 
    }
    
    public static function getPaymentParamsForMoney($amount) {
        $params = [];
        switch ($amount) {
            case \Yii::$app->params['yearSubscription']:
                $params['days'] = 365.25;
                $params['subscription'] = Plan::SUBSCRIPTION_YEAR;
                $params['paymentType'] = \common\models\Payment::TYPE_PRO_YEAR;
                break;
            case \Yii::$app->params['monthSubscription']:
                $params['days'] = (365.25) / 12;
                $params['subscription'] = Plan::SUBSCRIPTION_MONTH;
                $params['paymentType'] = \common\models\Payment::TYPE_PRO_MONTH;
                break;
            default:
                throw new Exception("Wrong money amount");
        }
        return $params;
    }

    public function completePayment(){
        // Get the payment Object by passing paymentId
	// payment id was previously stored in session in
	// CreatePaymentUsingPayPal.php
	$paymentId = $_SESSION['paymentId'];
	$payment = Payment::get($paymentId, $this->apiContext);
	
	// PaymentExecution object includes information necessary 
	// to execute a PayPal account payment. 
	// The payer_id is added to the request query parameters
	// when the user is redirected from paypal back to your site
	$execution = new PaymentExecution();
	$execution->setPayerId($_GET['PayerID']);
	
	//Execute the payment
	// (See bootstrap.php for more on `ApiContext`)
        try{
            $result = $payment->execute($execution, $this->apiContext);
        }catch (\Exception $ex) {
            return;
        }
        if ($result && $result->getState()=='approved'){
            $pay = \common\models\Payment::find()->where(['paypalId' => $result->getId()])->one();
            if ($pay && $pay->status == \common\models\Payment::STATUS_PENDING){
                $id = $pay->user->id;
                $plan = Plan::find($id);
                $plan->prolong($pay->amount);
                $pay->status = \common\models\Payment::STATUS_APPROVED;
                $pay->save();
            }
        }
    }
    public function proPayment($moneyAmount) {
//        $plan = Plan::find($user->id);
//        $plan->prolong($_POST['subscription']);
        $params = self::getPaymentParamsForMoney($moneyAmount);

        // A resource representing a Payer that funds a payment
        // For paypal account payments, set payment method
        // to 'paypal'.
        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        // ### Itemized information
        // (Optional) Lets you specify item wise
        // information
        $item = new Item();
        $item->setName('Pro account for ' . $params['subscription'])
                ->setCurrency('USD')
                ->setQuantity(1)
                ->setPrice($moneyAmount);
        $itemList = new ItemList();
        $itemList->setItems([$item]);


        $amount = new Amount();
        $amount->setCurrency("USD")
                ->setTotal($moneyAmount);

        // ### Transaction
        // A transaction defines the contract of a
        // payment - what is the payment for and who
        // is fulfilling it. 
        $transaction = new Transaction();
        $transaction->setAmount($amount)
                ->setItemList($itemList);

        $baseUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $baseUrl = preg_replace('/\?.*/', '', $baseUrl);
        
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($baseUrl.'?paymentSuccess=true')
                ->setCancelUrl($baseUrl.'?paymentSuccess=false');
//        var_dump($redirectUrls);die;

        // ### Payment
        // A Payment Resource; create one using
        // the above types and intent set to 'sale'
        $payment = new Payment();
        $payment->setIntent("sale")
                ->setPayer($payer)
                ->setRedirectUrls($redirectUrls)
                ->setTransactions([$transaction]);

        try {
            $payment->create($this->apiContext);
            // ### Get redirect url
            // The API response provides the url that you must redirect
            // the buyer to. Retrieve the url from the $payment->getLinks()
            // method
            foreach ($payment->getLinks() as $link) {
                if ($link->getRel() == 'approval_url') {
                    $redirectUrl = $link->getHref();
                    break;
                }
            }

            // ### Redirect buyer to PayPal website
            // Save the payment id so that you can 'complete' the payment
            // once the buyer approves the payment and is redirected
            // back to your website.
            //
            $_SESSION['paymentId'] = $payment->getId();
            $pay = new \common\models\Payment();
            $pay->paypalId = $payment->getId();
            $pay->userId = \Yii::$app->user->id;
            $pay->amount = $moneyAmount;
            $pay->type = $params['paymentType'];
            $pay->save();

            if (isset($redirectUrl)) {
                header("Location: $redirectUrl");
                exit;
            }
        } catch (\Exception $ex) {
            echo "Exception: " . $ex->getMessage() . PHP_EOL;
            var_dump($ex->getData());
            exit(1);
        }
    }

}
