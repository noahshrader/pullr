<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 04.08.14
 * Time: 21:29
 */

namespace common\components;

use yii\base\Component;
use Yii;
use common\models\Payment;
use common\models\Donation;
use common\models\Campaign;

class FirstGivingPayment extends Component{

    const VALID_CALLBACK_MESSAGE = 'Valid Callback';
    const PAYMENT_ID_PARAM = 'pullr_payment_id';

    private $donation;
    private $firstGiving;
    private $config;

    /**
     * @param mixed $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }

    public function __construct($config = array()) {
        parent::__construct($config);
    }

    /**
     * @param mixed $firstGiving
     */
    public function setFirstGiving($firstGiving)
    {
        $this->firstGiving = $firstGiving;
    }

    /**
     * @return mixed
     */
    public function getFirstGiving()
    {
        return $this->firstGiving;
    }

    /**
     * @param mixed $donation
     */
    public function setDonation($donation)
    {
        $this->donation = $donation;
    }

    /**
     * @return mixed
     */
    public function getDonation()
    {
        return $this->donation;
    }

    /**
     * Return donation First Giving form url
     * Create required objects in DB
     * Return form url for iframe
     *
     * @author Sergey Bulychev <svbulychev@gmail.com>
     * @return string $formUrl
    */
    public function donationPayment() {

        if (empty($this->config)) {
            throw new \InvalidArgumentException('Config can not be empty!');
        }

        //create payment

        $pay = new Payment();
        if (!\Yii::$app->user->isGuest) {
            $pay->userId = \Yii::$app->user->id;
        }
        $pay->amount = $this->donation->amount;
        $pay->type = Payment::TYPE_DONATION;
        $pay->relatedId = $this->donation->id;
        $pay->createdDate = time();
        $pay->save();

        //build success callback url

        //key and value for control callback from First Giving
        $key = ""; $value = "";
        extract($this->config['callbackSuccessPair']);

        $callbackUrl = sprintf('%s/?%s=%s&%s=%d', Yii::$app->request->hostInfo, $key, $value, self::PAYMENT_ID_PARAM, $pay->id);


        //build form url

        $styleSheetUrl = $this->config['formStyleSheetURL'];
        if (!parse_url($styleSheetUrl, PHP_URL_HOST)) {
            $styleSheetUrl = Yii::$app->urlManager->hostInfo . $styleSheetUrl;
        }

        $formUrl = sprintf(
            '%s/secure/payment/%s?amount=%s&_pb_success=%s&buttonText=%s&styleSheetURL=%s&affiliate_id=%s&_cb_success=%s',
            $this->config['donateHost'], // donate host
            $this->firstGiving->organization_uuid, // First Giving organization uuid
            $this->donation->amount, //amount
            base64_encode($this->config['pb_success']), // _pb_success
            $this->config['buttonText'], // buttonText
            base64_encode($styleSheetUrl), // styleSheetURL
            'Pullr', //affiliate_id
            base64_encode($callbackUrl) // _cb_success
        );

        if ($this->donation->email) {
            $formUrl .= sprintf('&email=%s', $this->donation->email);
        }

        return $formUrl;
    }


    /**
     * Process callback from First Giving to verify donation
     *
     * @author Sergey Bulychev <svbulychev@gmail.com>
     * @param \yii\web\Request $request
     * @return boolean result operation
    */
    public function completePayment($request) {

        if ($signature = $request->headers->get('Fg-Popup-Signature')) {

            //build url for verify

            $url = "";

            $output = self::sendCurlRequest($ch, $url);

            $serviceResponse = json_decode($output, true);

            if ($serviceResponse['status'] && $serviceResponse['message'] == self::VALID_CALLBACK_MESSAGE) {

                $pay = Payment::findOne(['id' => $request->get(self::PAYMENT_ID_PARAM)]);
                if ($pay && $pay->status == Payment::STATUS_PENDING) {
                    $pay->status = Payment::STATUS_APPROVED;
                    $pay->paymentDate = time();
                    $pay->firstGivingTransactionId = $request->get('_fg_popup_transaction_id');
                    $pay->save();
                    switch ($pay->type) {
                        case Payment::TYPE_DONATION:
                            $donation = Donation::findOne($pay->relatedId);
                            $donation->paymentDate = $pay->paymentDate;
                            if (!$donation->email && $request->get('_fgp_email')) {
                                $donation->email = strip_tags($request->get('_fgp_email'));
                            }

                            //first and last name
                            if ($fullName = $request->get('_fg_popup_donor_name')) {
                                $fullName = strip_tags($fullName);
                                $explodedName = explode(' ', $fullName);
                                $firstName = array_shift($explodedName);
                                $lastName = implode(' ', $explodedName);

                                $donation->firstName = $firstName;
                                $donation->lastName = $lastName;
                            }

                            $donation->save();
                            Campaign::updateDonationStatistics($donation->campaignId);
                            break;
                        default:
                            break;
                    }

                    return true;
                }
            }
        }

        return false;
    }

    public static function sendCurlRequest(&$ch, $url, $options = array(CURLOPT_RETURNTRANSFER => 1)) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        if (!empty($options)) {
            foreach ($options as $key => $value) {
                curl_setopt($ch, $key, $value);
            }
        }

        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }
} 