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
                <legend>Active Donations/Campaigns</legend>
                <div class="text-center">
                    <div class='report-label'>Total Amount Raised (across all campaigns)</div>
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
                        <div class='report-label'>Total # of Campaigns</div>
                        <div class='report-value'><?= $totalCampaigns?></div>
                    </div>
                    <div class="col-xs-4">
                        <div class='report-label'>Current Campaigns</div>
                        <div class='report-value'><?= $currentCampaigns?></div>
                    </div>
                    <div class="col-xs-4">
                        <div class='report-label'>Campaigns This Month</div>
                        <div class='report-value'><?= $campaignsThisMonth?></div>
                    </div>
                </div>
                <div class="text-center row">
                    <div class="col-xs-6">
                        <div class='report-label'>Top Selected Charity </div>
                        <div class='report-value'><?= $topSelectedCharity ? $topSelectedCharity->name : 'Not any charity selected' ?></div>
                    </div>
                    <div class="col-xs-6">
                        <div class='report-label'>Most Profitable Charity</div>
                        <div class='report-value'><?= $topProfitCharity ?  $topProfitCharity->name : 'Not any charity selected' ?></div>
                    </div>
                </div>
                 <div class="text-center row">
                    <div class="col-xs-6">
                        <div class='report-label'>User with Most Campaigns</div>
                        <div class='report-value'><?= $userWithMostCampaigns->name ?></div>
                    </div>
                    <div class="col-xs-6">
                        <div class='report-label'>Top Earning User</div>
                        <div class='report-value'><?= $topProfitUser->name ?></div>
                    </div>
                </div>
        </fieldset>
    </div>
</div>
