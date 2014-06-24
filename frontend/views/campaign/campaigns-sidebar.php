<?
use common\models\Campaign;
use yii\helpers\Html;
?>

<h2><?= Html::encode($this->title) ?> <a href="app/campaign/add" style="float:right; margin-right: 10px; color: #fff" ><i class="icon icon-add2"></i></a></h2>

<nav class="campaign-quicknav">
    <ul>
        <li><a href="app/campaign" class="<?= $status == Campaign::STATUS_ACTIVE ? 'active': '' ?>">Current</a></li>
        <li><a href="app/campaign/archive" class="<?= $status == Campaign::STATUS_PENDING ? 'active': '' ?>">Archive</a></li>
        <li><a href="app/campaign/donors" class="<?= $donorsSelected ? 'active': '' ?>">Donors</a></li>
        <li><a href="app/campaign/trash" class="<?= $status == Campaign::STATUS_DELETED ? 'active': '' ?>"><i class="glyphicon glyphicon-trash"></i></a></li>
    </ul>
</nav>