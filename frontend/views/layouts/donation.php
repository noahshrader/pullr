<?php
use yii\helpers\Html;
use common\widgets\user\UserPhoto;
use frontend\assets\DonationAsset;
/**
 * @var \yii\web\View $this
 * @var string $content
 */
DonationAsset::register($this);


$campaign = \Yii::$app->controller->campaign;
?>

<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
        <title><?= Html::encode($this->title) ?></title>
        <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,700' rel='stylesheet' type='text/css'>
        <base href="<?= \Yii::$app->urlManager->createUrl('/'); ?>">
        <?php $this->head() ?>
    </head>
    <body>
       <!-- BEGIN Progress Bar -->
        <div class="form-progress">
                <div class="form-progress-wrap">
                        <div class="progress" style="width:40%;"></div>
                        <div class="separation">
                                <div class="separation-left"></div>
                                <div class="separation-right"></div>
                        </div>
                </div>
                <div class="totals">
                    <span class="total">$<?= number_format($campaign->amountRaised) ?></span> of <span class="goal">$<?= number_format($campaign->goalAmount) ?></span>
                </div>
        </div>
       <!-- ENG Progress Bar -->
       
       <?php $this->beginBody() ?>
                       <?= $content ?>
       <?php $this->endBody() ?>
       
       <!-- Footer -->
        <footer id="footer">
                <h5>Powered by</h5>
                <a class="logo icon-pullr" href="http://www.pullr.io" target="_blank"></a>
        </footer>
    </body>
</html>
<?php $this->endPage() ?>
