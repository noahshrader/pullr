<?
namespace frontend\controllers;

use Yii;
use yii\base\Controller;
use yii\base\ErrorException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\web\Response;
use common\models\Campaign;
use common\models\Donation;
use frontend\models\helpers\PullrStatistic;
class StatisticController extends FrontendController {

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','amount_raised', 'api'],
                        'allow' => true
                    ],
                    [
                        'allow' => true,
                        'roles' => ['frontend'],
                    ],
                ],
            ],
        ];
    }

    public function init() {
        \Yii::$app->response->headers->set('Access-Control-Allow-Origin', '*');
        \Yii::$app->response->headers->set('Access-Control-Allow-Methods', 'GET, PUT, POST, DELETE, OPTIONS');
        \Yii::$app->response->headers->set('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, streamboardToken');

        if (\Yii::$app->request->isOptions)
        {
            echo 'Invalid request';
            \Yii::$app->end();
        }

        return parent::init();
    }

    public function getAmountRaised()
    {
        $pullrStatistic = new PullrStatistic();
        $cacheKey = 'pullr_statistic_amount_raised';
        $result = \Yii::$app->cache->get($cacheKey);
        if ( false === $result ) {
            $result = [
                'totalAmount' => $pullrStatistic->getTotalDonation(),
                'totalCharityAmount' => $pullrStatistic->getTotalDonationFromCharityCampaign(),
                'totalPersonalAmount' => $pullrStatistic->getTotalDonationFromPersonalCampaign()
            ];
            \Yii::$app->cache->set($cacheKey, $result, 1800);
        }
        return $result;
    }

    public function actionAmount_raised()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $result = $this->getAmountRaised();
        return $result;
    }

    public function actionApi()
    {
        echo $this->renderFile('@frontend/views/api/statistic.js');
    }
}