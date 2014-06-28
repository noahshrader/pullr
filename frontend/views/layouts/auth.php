<?php
use frontend\assets\FrontendAsset;
use common\assets\CommonAsset;
/**
 * @var \yii\web\View $this
 * @var string $content
 */
CommonAsset::register($this);
FrontendAsset::register($this);


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