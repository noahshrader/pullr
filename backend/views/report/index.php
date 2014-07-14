<?php

use yii\helpers\Html;
/**
 * @var yii\web\View $this
 */
$this->title = 'Reports';
?>
<div class="admin-content-wrap">

    <div class="row report">
        
        <div class="col-md-6">

            <h2>Active Users</h2>

                <div class="text-center row stats-overview">
                    <div class="stats-box">
                        <span class='report-value'><?= $totalUsers ?></span>
                        <div class='report-label'>Total Registered Users</div>
                    </div>
                </div>

                <div class="text-center row stats-overview">
                    <div class="col-xs-6 stats-box">
                        <span class='report-value'><?= $basicPlanUsers ?></span>
                        <div class='report-label'>Basic Plans Users</div>
                    </div>
                    <div class="col-xs-6 stats-box">
                        <span class='report-value'><?= $proPlanUsers ?></span>
                        <div class='report-label'>Pro Plans Users</div>
                    </div>
                </div>

                <div class="text-center row stats-overview">
                    <div class="stats-box">
                        <span class='report-value'>$<?= number_format($totalRevenue, 0)?></span>
                        <div class='report-label'>Total Revenue From Pro Subscriptions</div>
                    </div>
                </div>

                <div class="text-center row stats-overview">
                    <div class="col-xs-6 stats-box">
                        <span class='report-value'><?= $proPlanUsersMonthlyBased ?></span>
                        <div class='report-label'># of Monthly-Based Subscriptions</div>
                    </div>
                    <div class="col-xs-6 stats-box">
                        <span class='report-value'><?= $proPlanUsersYearlyBased ?></span>
                        <div class='report-label'># of Yearly-Based Subscriptions</div>
                    </div>
                </div>

                <div class="text-center row stats-overview">
                    <div class="col-xs-6 stats-box">
                        <span class='report-value'><?= $averageMonths ?> Months</span>
                        <div class='report-label'>Average Length of Pro Subscription</div>
                    </div>
                    <div class="col-xs-6 stats-box">
                        <span class='report-value'><?= $retentionRate ?>%</span>
                        <div class='report-label'>Retention Rate</div>
                    </div>
                </div>

        </div>


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
