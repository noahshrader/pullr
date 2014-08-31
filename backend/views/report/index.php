<?php

use yii\helpers\Html;
/**
 * @var yii\web\View $this
 */
$this->title = 'Reports';
?>
<div class="admin-content-wrap">

    <div class="row report">
        
        <fieldset class="col-md-6">
                
                <legend>Active Users</legend>

                <div class="stats-overview">

                    <div class="text-center row">
                        <div class="stats-box">
                            <span class='report-value'><?= $totalUsers ?></span>
                            <div class='report-label'>Total Registered Users</div>
                        </div>
                    </div>

                    <div class="text-center row">
                        <div class="col-sm-6 stats-box">
                            <span class='report-value'><?= $basicPlanUsers ?></span>
                            <div class='report-label'>Basic Plans Users</div>
                        </div>
                        <div class="col-sm-6 stats-box">
                            <span class='report-value'><?= $proPlanUsers ?></span>
                            <div class='report-label'>Pro Plans Users</div>
                        </div>
                    </div>

                    <div class="text-center row">
                        <div class="stats-box">
                            <span class='report-value'>$<?= number_format($totalRevenue, 0)?></span>
                            <div class='report-label'>Total Revenue From Pro Subscriptions</div>
                        </div>
                    </div>

                    <div class="text-center row">
                        <div class="col-sm-6 stats-box">
                            <span class='report-value'><?= $proPlanUsersMonthlyBased ?></span>
                            <div class='report-label'># of Monthly-Based Subscriptions</div>
                        </div>
                        <div class="col-sm-6 stats-box">
                            <span class='report-value'><?= $proPlanUsersYearlyBased ?></span>
                            <div class='report-label'># of Yearly-Based Subscriptions</div>
                        </div>
                    </div>

                    <div class="text-center row">
                        <div class="col-sm-6 stats-box">
                            <span class='report-value'><?= $averageMonths ?> Months</span>
                            <div class='report-label'>Average Length of Pro Subscription</div>
                        </div>
                        <div class="col-sm-6 stats-box">
                            <span class='report-value'><?= $retentionRate ?>%</span>
                            <div class='report-label'>Retention Rate</div>
                        </div>
                    </div>

                </div>

        </fieldset>

        <fieldset class="col-md-6">

                <legend>Active Donations/Campaigns</legend>

                <div class="stats-overview">

                    <div class="text-center row">
                        <div class="stats-box">
                            <span class='report-value'><?= number_format($totalAmountRaised) ?></span>
                            <div class='report-label'>Total Amount Raised (across all campaigns)</div>
                        </div>
                    </div>

                    <div class="text-center row">
                        <div class="col-sm-6 stats-box">
                            <span class='report-value'><?= number_format($amountCurrentlyBeingRaised) ?></span>
                            <div class='report-label'>Amount Currently Being Raised </div>
                        </div>
                        <div class="col-sm-6 stats-box">
                            <span class='report-value'><?= number_format($amountRaisedThisMonth) ?></span>
                            <div class='report-label'>Amount Raised This Month</div>
                        </div>
                    </div>

                    <div class="text-center row">
                        <div class="col-sm-4 stats-box">
                            <span class='report-value'><?= $totalCampaigns?></span>
                            <div class='report-label'>Total # of Campaigns</div>
                        </div>
                        <div class="col-sm-4 stats-box">
                            <span class='report-value'><?= $currentCampaigns?></span>
                            <div class='report-label'>Current Campaigns</div>
                        </div>
                        <div class="col-sm-4 stats-box">
                            <span class='report-value'><?= $campaignsThisMonth?></span>
                            <div class='report-label'>Campaigns This Month</div>
                        </div>
                    </div>

                    <div class="text-center row">
                        <div class="col-sm-6 stats-box">
                            <span class='report-value'><?= $topSelectedCharity ? $topSelectedCharity->name : 'Not any charity selected' ?></span>
                            <div class='report-label'>Top Selected Charity </div>
                        </div>
                        <div class="col-sm-6 stats-box">
                            <span class='report-value'><?= $topProfitCharity ?  $topProfitCharity->name : 'Not any charity selected' ?></span>
                            <div class='report-label'>Most Profitable Charity</div>
                        </div>
                    </div>

                     <div class="text-center row">
                        <div class="col-sm-6 stats-box">
                            <span class='report-value'><?= $userWithMostCampaigns->name ?></span>
                            <div class='report-label'>User with Most Campaigns</div>            
                        </div>
                        <div class="col-sm-6 stats-box">
                            <span class='report-value'><?= $topProfitUser->name ?></span>
                            <div class='report-label'>Top Earning User</div>
                        </div>
                    </div>

                </div>

        </fieldset>
    </div>
</div>
