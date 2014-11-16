<?
use yii\helpers\Html;
use common\models\Donation;
use common\models\User;

/**
 * @var \yii\web\View $this
 */
?>
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title ng-if='! campaign'>Pullr</title>
    <title ng-if='campaign' ng-cloak>{{campaign.name}} - Pullr</title>
    <base href="<?= \Yii::$app->urlManager->createUrl('/'); ?>">
    <link href="favicon.ico" rel="shortcut icon">
    <?php $this->head() ?>
    <script>
        <?
        $js = 'window.Pullr = window.Pullr || {}; ';
        $js .= 'window.Pullr.twitchClientId = "'. \Yii::$app->params['twitchClientId'].'";';
        $js .= 'window.Pullr.ANONYMOUS_NAME = "' . Donation::ANONYMOUS_NAME . '";';
        $js .= 'Pullr.ENV = "'. YII_ENV.'";';
        $publicParams = ['googleAPIKey'];
        $params = array_intersect_key(Yii::$app->params, array_flip($publicParams));
        $js .= 'Pullr.params = '.json_encode($params).';';
        $onreadyJs = '';
        if (!\Yii::$app->user->isGuest) {
           $user = \Yii::$app->user->identity;
           /**@var User $user*/
           $js .= 'window.Pullr.user = ' . json_encode($user->toArray()) . ';';
           $onreadyJs .= 'twitchEventsMonitor();';
        }
        echo $js;
        $this->registerJs($onreadyJs);
        ?>
    </script>
    <script src="api/script"></script>

    <!-- Load Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,500,700|Varela+Round' rel='stylesheet' type='text/css'>

    <!-- CSS -->
    <style>
        h2.main-title,
        h3.charity-name a.approved i,
        span.amountRaised,
        p.info a,
        a.close {
            color: {{campaign.primaryColor}} !important;
        }
        h3.charity-name a.approved .approved-info:before {
            border-color: transparent transparent {{campaign.primaryColor}} transparent !important;
        }
        .progress,
        h3.charity-name a.approved .approved-info,
        .events-form #donation-amount .fieldamount label.active,
        button.donate {
            background: {{campaign.primaryColor}} !important;
        }
    </style>
</head>