<?php
use yii\helpers\Html;
use common\assets\DonationAsset;
/**
 * That is layout for Donation Form. Which is loaded by Magnific popup
 * @var \yii\web\View $this
 * @var string $content
 */
DonationAsset::register($this);

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
        </footer>
    </body>
</html>
<?php $this->endPage() ?>