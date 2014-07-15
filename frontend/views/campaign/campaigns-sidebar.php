<?
use common\models\Campaign;
use yii\helpers\Html;
?>


<nav class="campaign-quicknav">
    <ul>
        <li><a href="app/campaign" class="<?= $status == Campaign::STATUS_ACTIVE ? 'active': '' ?>">Current</a></li>
        <li><a href="app/campaign/archive" class="<?= $status == Campaign::STATUS_PENDING ? 'active': '' ?>">Archive</a></li>
        <li><a href="app/campaign/donors" class="<?= $donorsSelected ? 'active': '' ?>">Donors</a></li>
        <li><a href="app/campaign/trash" class="<?= $status == Campaign::STATUS_DELETED ? 'active': '' ?>">Trash</a></li>
    </ul>
</nav>