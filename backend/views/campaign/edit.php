<?php

use common\models\Campaign;
use yii\helpers\Html; 
?>

<h1><?= Html::encode($campaign->name) ?></h1>

<div>
    <? if ($campaign->status != Campaign::STATUS_DELETED): ?>
        <a href="campaign/delete?id=<?= $campaign->id ?>" class="btn btn-danger">Deactivate campaign</href>
    <? endif; ?>
    <a href="campaign" class="btn btn-link">Back</href>
</div>
