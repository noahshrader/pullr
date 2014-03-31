<?php

use yii\helpers\Html;
/**
 * @var yii\web\View $this
 */
$this->title = 'Reports';
?>
<div>
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row report">
        <fieldset class="col-md-6">
                <legend>Active Users</legend>
                <div class="text-center">
                    <div class='report-label'>Total Registered Users</div>
                    <div class='report-value'><?= $totalUsers ?></div>
                </div>
                <div class="text-center row">
                    <div class="col-xs-6">
                        <div class='report-label'>Basic Plans Users</div>
                        <div class='report-value'><?= $basicPlanUsers ?></div>
                    </div>
                    <div class="col-xs-6">
                        <div class='report-label'>Pro Plans Users</div>
                        <div class='report-value'><?= $proPlanUsers ?></div>
                    </div>
                </div>
                <div class="text-center">
                    <div class='report-label'>Total Revenue From Pro Subscriptions</div>
                    <div class='report-value'>$<?= number_format($totalRevenue, 0)?></div>
                </div>
                <div class="text-center row">
                    <div class="col-xs-6">
                        <div class='report-label'># of Monthly-Based Subscriptions</div>
                        <div class='report-value'><?= $proPlanUsersMonthlyBased ?></div>
                    </div>
                    <div class="col-xs-6">
                        <div class='report-label'># of Yearly-Based Subscriptions</div>
                        <div class='report-value'><?= $proPlanUsersYearlyBased ?></div>
                    </div>
                </div>
        </fieldset>
        <fieldset class="col-md-6">
                <legend>Active Donations/Events</legend>
        </fieldset>
    </div>
</div>
