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
    <title><?= Html::encode($this->title) ?></title>
    <base href="<?= \Yii::$app->urlManager->createUrl('/'); ?>">
    <?php $this->head() ?>

    <!-- Load Typekit Fonts -->
    <script type="text/javascript" src="//use.typekit.net/qke3nuw.js"></script>
    <script type="text/javascript">try {
            Typekit.load();
        } catch (e) {
        }</script>
    <script type="text/javascript">
        <?
        $js = 'window.Pullr = window.Pullr || {}; ';
        $js .= 'window.Pullr.twitchClientId = "'. \Yii::$app->params['twitchClientId'].'";';
        $js .= 'window.Pullr.ANONYMOUS_NAME = "' . Donation::ANONYMOUS_NAME . '";';
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
</head>