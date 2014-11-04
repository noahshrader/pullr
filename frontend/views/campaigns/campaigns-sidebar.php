<?
use common\models\Campaign;
use yii\helpers\Html;
?>

<ul class="campaign-quicknav panel-nav cf">
	<li class="<?= $status == Campaign::STATUS_ACTIVE ? 'active': '' ?>"><a href="app/campaigns" class="icon-piechart2"></a></li>
	<li class="<?= $status == Campaign::STATUS_PENDING ? 'active': '' ?>"><a href="app/campaigns/archive" class="icon-archive"></a></li>
	<li class="<?= $status == Campaign::STATUS_DELETED ? 'active': '' ?>"><a href="app/campaigns/trash" class="icon-trash"></a></li>
</ul>