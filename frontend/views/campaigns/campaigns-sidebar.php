<?
use common\models\Campaign;
use yii\helpers\Html;
?>

<ul class="campaign-quicknav panel-nav cf">
	<li class="<?= $status == Campaign::STATUS_ACTIVE ? 'active': '' ?>"><a href="app/campaigns" class="mdi-av-equalizer"></a></li>
	<li class="<?= $status == Campaign::STATUS_PENDING ? 'active': '' ?>"><a href="app/campaigns/archive" class="mdi-content-inbox"></a></li>
	<li class="<?= $status == Campaign::STATUS_DELETED ? 'active': '' ?>"><a href="app/campaigns/trash" class="mdi-action-delete"></a></li>
</ul>