<?
use yii\helpers\Url;
?>
<div data-ng-app="streamboardApp">

<!-- // Layout Options Side Panel // -->
<section id="sidepanel" class="sidepanel open resizable">
	<div class="sidepanel-head">
		<ul class="nav nav-tabs three-tabs cf">
			<li class="active">
                <a href="<?= Url::to()?>#donations" data-toggle="tab" class="donations">Donations</a>
            </li>
			<li><a href="<?= Url::to()?>#region_1" data-toggle="tab" class="region1">Region 1</a></li>
			<li><a href="<?= Url::to()?>#region_2" data-toggle="tab" class="region2">Region 2</a></li>
		</ul>
	</div>
	<div class="tab-content">
		<!-- Accordion slide one (Donations) -->
		<div class="tab-pane active" id="donations" data-ng-controller="DonationsController" data-ng-init="start_time = <?php echo time(); ?>;">

            <input type="button" value="Create donation" data-ng-click="addDonation()" >
            <div class="donations_list">
               <div data-ng-repeat="donation in donations" class="donation">
                   <div class="donation_name">{{donation.name}}</div>
                   <div class="donation_amount">${{donation.amount}}</div>
                   <div class="donation_campaign">{{donation.campaign_name}}</div>
                   <div class="donation_comments">{{donation.comments}}</div>
                   <div class="donation_date">{{donation.date_formatted}}</div>
               </div>
            </div>

			<ul class="bottom-panel-nav two-tabs cf">
				<li><a data-panel="campaigns_list">Campaigns</a></li>
				<li><a data-panel="stats">Stats</a></li>
			</ul>
            <div class="campaigns_list_panel slidepanel">
                <div class="campaigns_list">
                    <h3>Campaigns</h3>
                    <?php
                        foreach($campaigns as $campaign) {
                    ?>
                        <input type="checkbox" name="campaigns[]"
                            value="<?php echo $campaign['id']; ?>" checked="checked"
                            id="campaign_<?php echo $campaign['id']; ?>"
                            data-ng-init="campaigns.<?php echo $campaign['id']; ?> = true"
                            data-ng-model="campaigns.<?php echo $campaign['id']; ?>"
                            />
                        <label for="campaign_<?php echo $campaign['id']; ?>">
                            <?php echo $campaign['name']; ?>
                        </label>
                        <br>
                    <?php
                        }
                    ?>

                </div>
                <a class="close icon-cross"></a>
            </div>
            <div class="stats_panel slidepanel">
                <h3>Stats</h3>
                <label>Your Top 3 Donors</label>
                <ul class="top_donors">
                    <li data-ng-repeat="donor in stats.top_donors">
                        {{donor.name}}
                    </li>
                </ul>

                <label>Total Donation Amount</label>
                <span class="total_amount" >
                    ${{stats.total_amount}}
                </span>

                <label>Top Donation Amount</label>
                <span class="top_donation" >
                    ${{stats.top_donation_amount}} ( {{stats.top_donation_name}} )
                </span>

                <label>Number Of Donations</label>
                <span class="number_of_donations" >
                    {{stats.number_of_donations}}
                </span>

                <label>Number Of Donors</label>
                <span class="number_of_donors" >
                    {{stats.number_of_donors}}
                </span>

                <a class="close icon-cross"></a>
            </div>

		</div>
		<!-- Accordion slide two (Region 1) -->
		<div class="tab-pane" id="design">
			<h3>Region 1</h3>
		</div>
		<!-- Accordion slide three (Region 2) -->
		<div class="tab-pane" id="region_1">
			<h3>Region 2</h3>
            <iframe src="http://webdesignermemphis.com/pullrapp/streamboard/source.html" allowtransparency="true"></iframe>

        </div>
    </div>
</section>

</div>
