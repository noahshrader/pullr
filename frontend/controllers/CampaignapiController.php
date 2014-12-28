<?php

namespace frontend\controllers;

use common\models\base\BaseImage;
use common\models\Plan;
use yii\web\Response;
use yii\base\Exception;
use yii\web\ForbiddenHttpException;
use common\models\User;
use common\models\Campaign;
use common\models\Donation;
use common\models\twitch\TwitchFollow;
use common\components\PullrUtils;
use Yii;

class CampaignapiController extends \yii\web\Controller
{

    const PRIVATE_MODE = true;

    protected $_user = null;

    public function init()
    {
        $this->enableCORS();
        Yii::$app->response->format = Response::FORMAT_JSON;
        $apiKey = \Yii::$app->request->getQueryParam('apikey');
        $user = $this->getUserByApiKey($apiKey);
        if ($user == "NOT_USER") {
            $this->throwError("401", "An authentication error occured");
        }
        $this->setUser($user);
        return parent::init();
    }

    protected function getUserByApiKey($apiKey)
    {
        $user = User::findOne(['apiKey' => $apiKey]);
        if (count($user)) {
            return $user;
        } else {
            return "NOT_USER";
        }
    }

    protected function setUser($user)
    {
        $this->_user = $user;
        return true;
    }

    protected function getUser()
    {
        return $this->_user;
    }

    protected function enableCORS()
    {
        \Yii::$app->response->headers->set('Access-Control-Allow-Origin', '*');
        \Yii::$app->response->headers->set('Access-Control-Allow-Methods', 'GET, PUT, POST, DELETE, OPTIONS');
        \Yii::$app->response->headers->set('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept');
    }

    public function actionIndex()
    {
        return array();
    }

    /**
     * Action for get all campaign
     *
     * @param string status
     * @param string sort
     * @param int offset
     * @param int limit
     *
     */
    public function actionAllcampaigns()
    {
        $params = \Yii::$app->request->getQueryParams();
        $defaultParams = array(
            'status' => Campaign::STATUS_ACTIVE,
            'offset' => 0,
            'limit' => 0,
            'sort' => '-id'
        );
        $user = $this->getUser();
        $params = array_merge($defaultParams, $params);
        /**
         * check params
         */
        // check status
        if (in_array($params['status'], array(Campaign::STATUS_ACTIVE, Campaign::STATUS_PENDING, Campaign::STATUS_DELETED)) == false) {
            $this->throwError("407", "status param is wrong");
        }
        //check sort
        if (substr($params['sort'], 0, 1) == '-') {
            $params['sort'] = substr($params['sort'], 1);
            $orderAttr = 'DESC';
        } else {
            $orderAttr = 'ASC';
        }
        if (in_array($params['sort'], array('id', 'name', 'amountRaised', 'goalAmount')) == false) {
            $this->throwError("407", "sort prama is wrong");
        }

        $sql = $user->getCampaigns($params['status'], false)->orderBy(sprintf('%s %s', $params['sort'], $orderAttr));
        if ($params['limit'] != 0) {
            $sql->offset($params['offset'])->limit($params['limit']);
        }
        $data = array();
        if ($sql->count() > 0) {
            foreach ($sql->all() as $capaign) {
                $data[] = array(
                    'id' => $capaign['id'],
                    'name' => $capaign['name'],
                    'description' => $capaign['description'],
                    'alias' => $capaign['alias'],
                    'amountRaised' => $capaign['amountRaised'],
                    'goalAmount' => $capaign['goalAmount'],
                    'type' => $capaign['type'],
                    'startDate' => $capaign['startDate'],
                    'endDate' => $capaign['endDate'],
                    'status' => $capaign['status'],
                    'numberOfDonations' => $capaign['numberOfDonations'],
                    'numberOfUniqueDonors' => $capaign['numberOfUniqueDonors'],
                    'date' => $capaign['date']
                );
            }
        }
        return $data;
    }


    /**
     * Action for get Campaing
     *
     * @param  string campaignAlias
     */
    public function actionCampaign()
    {
        $campaignAlias = \Yii::$app->request->getQueryParam('campaignAlias');
        $user = $this->getUser();
        $campaign = Campaign::findOne(['userId' => $user->id, 'status' => Campaign::STATUS_ACTIVE, 'alias' => $campaignAlias]);
        $response = array();
        if (count($campaign) != 0) {
            $response = $campaign->toArray(['amountRaised', 'goalAmount', 'startDate', 'endDate']);
            $response['name'] = $campaign->name;
            $response['description'] = $campaign->description;
            $response['alias'] = $campaign->alias;
            $response['goalAmount'] = $campaign->goalAmount;
            $response['amountRaised'] = $campaign->amountRaised;
            $response['numberOfDonations'] = $campaign->getDonations()->count();
            $response['numberOfDonors'] = $campaign->getDonations()->count('DISTINCT email');
//            $donationsArray = [];
//            $donations = $campaign->getDonations()->all();
//            foreach ($donations as $donation) {
//                $donationsArray[] = $donation->toArray(['id', 'amount', 'nameFromForm', 'comments', 'paymentDate']);
//            }
            $response['donations'] = [];//;$donationsArray;
        }
        return $response;
    }

    /**
     *  Action get Donors by campaignAlias
     */
    public function actionDonationlist()
    {
        $campaignAlias = \Yii::$app->request->getQueryParam('campaignAlias');
        if(!$campaignAlias){
            $this->throwError("407", "campaignAlias param is wrong");
        }
        $defaultParams = array(
            'offset' => 0,
            'limit' => 0,
            'sort' => '-paymentDate'
        );
        $params = \Yii::$app->request->getQueryParams();
        $params = array_merge($defaultParams, $params);

        $user = $this->getUser();

        /**
         * check params
         */
        //check sort
        if (substr($params['sort'], 0, 1) == '-') {
            $params['sort'] = substr($params['sort'], 1);
            $orderAttr = 'DESC';
        } else {
            $orderAttr = 'ASC';
        }
        if (in_array($params['sort'], array('id', 'nameFromForm', 'amount', 'paymentDate')) == false) {
            $this->throwError("407", "sort param is wrong");
        }
        $campaign = Campaign::findOne(['alias' => $campaignAlias]);

        $donationsArray = [];
        $sql = $campaign->getDonations()->orderBy(sprintf('%s %s', $params['sort'], $orderAttr));
        if ($params['limit'] != 0) {
            $sql->offset($params['offset'])->limit($params['limit']);
        }
        $donations = $sql->all();
        foreach ($donations as $donation) {
            $donationsArray[] = $donation->toArray(['id', 'amount', 'nameFromForm', 'comments', 'paymentDate']);
        }
        return $donationsArray;
    }

    /**
     *
     */
    public function actionDonation()
    {
        $id = \Yii::$app->request->getQueryParam('id');
        $donation = Donation::findOne($id);
        $data = array('id' => $donation->id,
            'nameFromForm' => $donation->nameFromForm,
            'comments' => $donation->comments,
            'amount' => $donation->amount,
            'paymentDate' => $donation->paymentDate);
        return $data;
    }

    public function throwError($setStatusCode, $errorMessage)
    {
        \Yii::$app->response->setStatusCode($setStatusCode);
        $errornames = array(
                                '401'=>'Unauthorized',
                                '405'=>'MethodNotAllowed',
                                '406'=>'NotAcceptable',
                                '407'=>'InvalidParameter'
                            );
        echo json_encode(array('name' => $errornames[$setStatusCode], 'message' => $errorMessage));
        \Yii::$app->response->send();
        die();
    }
}
