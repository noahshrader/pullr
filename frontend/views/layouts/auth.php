<?php
/**
 * This layout is used for login page and some non-authenticated pages like "Privacy Policy" and "Terms of service".
 */
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
                        <h1><a class="login-logo icon-pullr"></a></h1>
                        <?= $content ?>
                    </div>
            </div>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>