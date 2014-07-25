<?php
use yii\helpers\Html;
use common\assets\CommonAsset;
use common\assets\streamboard\StreamboardCommonAsset;
use common\assets\streamboard\StreamboardSourceAsset;

CommonAsset::register($this);
StreamboardCommonAsset::register($this);
StreamboardSourceAsset::register($this);

$this->title = 'Streamboard';
?>

<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <?= $this->render('../baseHead') ?>
    <body>
		<?php $this->beginBody() ?>
		<?= $content ?>
		<?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>