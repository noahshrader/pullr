<?
use common\models\Campaign;
use yii\helpers\Html;
?>


<nav class="campaign-quicknav">
    <ul>
        <li><a href="app/campaign" class="icon-piechart <?= $status == Campaign::STATUS_ACTIVE ? 'active': '' ?>"></a></li>
        <li><a href="app/campaign/archive" class="icon-archivebox <?= $status == Campaign::STATUS_PENDING ? 'active': '' ?>"></a></li>
        <li><a href="app/campaign/trash" class="icon-trash <?= $status == Campaign::STATUS_DELETED ? 'active': '' ?>"></a></li>
    </ul>
</nav>