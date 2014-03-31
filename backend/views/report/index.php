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
                <div class="text-center row">
                    <div class="col-xs-6">
                        <div class='report-label'>Average Length of Pro Subscription</div>
                        <div class='report-value'><?= $averageMonths ?> months</div>
                    </div>
                    <div class="col-xs-6">
                        <div class='report-label'>Retention Rate</div>
                        <div class='report-value'><?= $retentionRate ?>%</div>
                    </div>
                </div>
        </fieldset>
        <fieldset class="col-md-6">
                <legend>Active Donations/Events</legend>
                <div class="text-center">
                    <div class='report-label'>Total Amount Raised (across all events)</div>
                    <div class='report-value'><?= number_format($totalAmountRaised) ?></div>
                </div>
                <div class="text-center row">
                    <div class="col-xs-6">
                        <div class='report-label'>Amount Currently Being Raised </div>
                        <div class='report-value'><?= number_format($amountCurrentlyBeingRaised) ?></div>
                    </div>
                    <div class="col-xs-6">
                        <div class='report-label'>Amount Raised This Month</div>
                        <div class='report-value'>none</div>
                    </div>
                </div>
                <div class="text-center row">
                    <div class="col-xs-4">
                        <div class='report-label'>Total # of Events</div>
                        <div class='report-value'><?= $totalEvents?></div>
                    </div>
                    <div class="col-xs-4">
                        <div class='report-label'>Current Events</div>
                        <div class='report-value'><?= $currentEvents?></div>
                    </div>
                    <div class="col-xs-4">
                        <div class='report-label'>Events This Month</div>
                        <div class='report-value'><?= $eventsThisMonth?></div>
                    </div>
                </div>
                <div class="text-center row">
                    <div class="col-xs-6">
                        <div class='report-label'>Top Selected Charity </div>
                        <div class='report-value'><?= $topSelectedCharity->name ?></div>
                    </div>
                    <div class="col-xs-6">
                        <div class='report-label'>Most Profitable Charity</div>
                        <div class='report-value'><?= $topProfitCharity->name ?></div>
                    </div>
                </div>
                 <div class="text-center row">
                    <div class="col-xs-6">
                        <div class='report-label'>User with Most Events</div>
                        <div class='report-value'><?= $userWithMostEvents->name ?></div>
                    </div>
                    <div class="col-xs-6">
                        <div class='report-label'>Top Earning User</div>
                        <div class='report-value'><?= $topProfitUser->name ?></div>
                    </div>
                </div>
        </fieldset>
    </div>
</div>
