<?php
use yii\helpers\Html;
use common\assets\CommonAsset;
use common\assets\streamboard\StreamboardAsset;
use common\assets\streamboard\StreamboardCommonAsset;

CommonAsset::register($this);
StreamboardCommonAsset::register($this);
StreamboardAsset::register($this);
$this->title = 'Streamboard';
?>

<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <?= $this->render('baseHead') ?>
    <body>
		<div class="spinner-wrap">
			<div class="spinner">
				<div class="cube1"></div>
				<div class="cube2"></div>
			</div>
		</div>
		<?php $this->beginBody() ?>
		<?= $content ?>
		<?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>