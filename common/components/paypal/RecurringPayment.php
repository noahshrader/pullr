<?php
namespace common\components\paypal;

use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use PayPal\Transport\PPRestCall;
use JsonMapper;
use PayPal\Api\Currency;
use Yii;

class RecurringPayment {
    public static function createRecurringPlan()
    {
        $apiContext = new ApiContext(new OAuthTokenCredential(\Yii::$app->params['paypalClientId'], \Yii::$app->params['paypalClientSecret']));
        $apiContext->setConfig(
            array(
                'mode' => 'sandbox',
                'http.ConnectionTimeOut' => 30,
                'log.LogEnabled' => true,
                'log.FileName' => '/var/www/pullr/PayPal.log',
                'log.LogLevel' => 'FINE'
            )
        );

        die($apiContext->getCredential()->getAccessToken($apiContext->getConfig()));

        $paymentDefinition = (new PaymentDefinition())
            ->setName("Regular payments")
            ->setType("REGULAR")
            ->setFrequency("MONTH")
            ->setFrequency_interval("2")
            ->setCycles("12")
            ->setAmount((new Currency())->setValue("100")->setCurrency("USD"))
            ->setCharge_models([
                (new ChargeModel())->setType("SHIPPING")->setAmount((new Currency())->setValue("10")->setCurrency("USD")),
                (new ChargeModel())->setType("TAX")->setAmount((new Currency())->setValue("12")->setCurrency("USD"))
            ]);

        $merchantPreferences = (new MerchantPreferences())
            ->setSetup_fee((new Currency())->setValue("1")->setCurrency("USD"))
            ->setReturn_url(Yii::$app->urlManager->createAbsoluteUrl('settings/prosteptwo'))
            ->setCancel_url("http://cancel.com")
            ->setAuto_bill_amount("YES")
            ->setInitial_fail_amount_action("CONTINUE")
            ->setMax_fail_attempts("10");

        $plan = (new \common\components\paypal\Plan())
            ->setName("test")
            ->setDescription("some descr here")
            ->setType("fixed")
            ->setPayment_definitions([$paymentDefinition])
            ->setMerchant_preferences($merchantPreferences);

        $call = new PPRestCall($apiContext);
        $responseJson = $call->execute(array('PayPal\Rest\RestHandler'), "/v1/payments/billing-plans", "POST", $plan->toJSON());

        return (new JsonMapper())->map(json_decode($responseJson), new Plan());
    }

    public static function createBillingAgreement($agreement)
    {
        $apiContext = new ApiContext(new OAuthTokenCredential(\Yii::$app->params['paypalClientId'], \Yii::$app->params['paypalClientSecret']));

        $apiContext->setConfig(
            array(
                'mode' => 'sandbox',
                'http.ConnectionTimeOut' => 30,
                'log.LogEnabled' => true,
                'log.FileName' => '/var/www/pullr/PayPal.log',
                'log.LogLevel' => 'FINE'
            )
        );

        $call = new PPRestCall($apiContext);
        $responseJson = $call->execute(array('PayPal\Rest\RestHandler'), "/v1/payments/billing-agreements", "POST", $agreement->toJSON());

        return (new JsonMapper())->map(json_decode($responseJson), new Agreement());
    }

    public function executeBillingAgreement($token){
        $apiContext = new ApiContext(new OAuthTokenCredential(\Yii::$app->params['paypalClientId'], \Yii::$app->params['paypalClientSecret']));

        $apiContext->setConfig(
            array(
                'mode' => 'sandbox',
                'http.ConnectionTimeOut' => 30,
                'log.LogEnabled' => true,
                'log.FileName' => '/var/www/pullr/PayPal.log',
                'log.LogLevel' => 'FINE'
            )
        );

        $call = new PPRestCall($apiContext);
        $responseJson = $call->execute(array('PayPal\Rest\RestHandler'), "/v1/payments/billing-agreements/{$token}/agreement-execute", "POST", "{}");

        return (new JsonMapper())->map(json_decode($responseJson), new Agreement());
    }
} 