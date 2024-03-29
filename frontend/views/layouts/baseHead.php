<?
use yii\helpers\Html;
use common\models\Donation;
use common\models\User;
use common\models\Campaign;
/**
 * @var \yii\web\View $this
 */
?>
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::encode($this->title) ?></title>
    <base href="<?= \Yii::$app->urlManager->createUrl('/'); ?>">
    <link href="favicon.ico" rel="shortcut icon">
    <?php $this->head() ?>
    <script>
        <?
        $js = 'window.Pullr = window.Pullr || {}; ';
        $js .= 'window.Pullr.twitchClientId = "'. \Yii::$app->params['twitchClientId'].'";';
        $js .= 'window.Pullr.ANONYMOUS_NAME = "' . Donation::ANONYMOUS_NAME . '";';
        $js .= 'window.Pullr.CAMPAIGN_TYPE_CHARITY_FUNDRAISER = "' . Campaign::TYPE_CHARITY_FUNDRAISER . '";';
        $js .= 'Pullr.ENV = "'. YII_ENV.'";';
        $publicParams = ['googleAPIKey'];
        $params = array_intersect_key(Yii::$app->params, array_flip($publicParams));
        $js .= 'Pullr.params = '.json_encode($params).';';

        if (!\Yii::$app->user->isGuest) {
           $user = \Yii::$app->user->identity;
           /**@var User $user*/
           $js .= 'window.Pullr.user = ' . json_encode($user->toArray()) . ';';

        }
        echo $js;
        ?>
    </script>
    <!-- Load Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,700' rel='stylesheet' type='text/css'>
</head>