<?php
use yii\helpers\Html;
use common\assets\DonationAsset;
/**
 * That is layout for Donation Form. Which is loaded by Magnific popup
 * @var \yii\web\View $this
 * @var string $content
 */
DonationAsset::register($this);

$campaign = \Yii::$app->controller->campaign;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <?= $this->render('baseHead') ?>
    <body class="campaignflow">

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