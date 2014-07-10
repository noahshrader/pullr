<?php
use yii\helpers\Html;
use common\assets\CommonAsset;
use frontend\assets\FrontendAsset;
use frontend\assets\StreamboardAsset;

//$this->registerJsFile('@web/js/campaign/edit.js', common\assets\CommonAsset::className());

CommonAsset::register($this);
FrontendAsset::register($this);
StreamboardAsset::register($this);

?>

<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
        <title><?= Html::encode($this->title) ?></title>
        <base href="<?= \Yii::$app->urlManager->createUrl('/'); ?>">
        <?php $this->head() ?>

    </head>
    <body>

       <?php $this->beginBody() ?>
                       <?= $content ?>
       <?php $this->endBody() ?>
       
    </body>
</html>
<?php $this->endPage() ?>
