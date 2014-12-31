<?php
use common\assets\CommonAsset;
use common\assets\AuthAsset;
/**
 * @var \yii\web\View $this
 * @var string $content
 */
CommonAsset::register($this);
AuthAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <?= $this->render('baseHead') ?>
    
    <body>
        <?php $this->beginBody() ?>
             <div class="intro-content-wrapper">
                    <div class="intro-content">
                        <div class="login-box animated zoomIn">
                            <a class="login-logo mdib-pullr-logo"></a>
                            <?= $content ?>
                        </div>
                    </div>
            </div>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>