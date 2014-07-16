<?
use yii\helpers\Html;
use common\models\Donation;
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
    <script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<?
$js = 'Pullr.ANONYMOUS_NAME = "'.Donation::ANONYMOUS_NAME.'";';
$this->registerJs($js);
?>
</head>