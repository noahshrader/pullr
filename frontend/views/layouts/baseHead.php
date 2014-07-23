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
    <?
    $js = 'window.Pullr.ANONYMOUS_NAME = "' . Donation::ANONYMOUS_NAME . '";';
    if (!\Yii::$app->user->isGuest) {
       $user = \Yii::$app->user->identity;
       /**@var User $user*/
       $js .= 'window.Pullr.user = ' . json_encode($user->toArray()) . ';';
       $js .= 'twitchEventsMonitor()';

    }
    $this->registerJs($js);
    ?>
</head>