<?
use common\models\Campaign;
use yii\helpers\Html;
?>

<ul class="campaign-quicknav panel-nav cf">
	<li class="<?= $status == Campaign::STATUS_ACTIVE ? 'active': '' ?>"><a href="app/campaign" class="icon-piechart"></a></li>
	<li class="<?= $status == Campaign::STATUS_PENDING ? 'active': '' ?>"><a href="app/campaign/archive" class="icon-archivebox"></a></li>
	<li class="<?= $status == Campaign::STATUS_DELETED ? 'active': '' ?>"><a href="app/campaign/trash" class="icon-trash"></a></li>
</ul>