<?php

use yii\helpers\Html;
/**
 * @var yii\web\View $this
 */
$this->title = 'Reports';
?>
<div>
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <fieldset class="col-md-6">
                <legend>Active Users</legend>
                <div class="text-center">
                    <div class='report-label'>Total Registered Users</div>
                    <div class='report-value'><?= $totalUsers ?></div>
                </div>
        </fieldset>
        <fieldset class="col-md-6">
                <legend>Active Donations/Events</legend>
        </fieldset>
    </div>
</div>
