<?php
	use common\models\Donation;
?>
<!-- Stats Panel Link -->
<ul class="panel-nav paneltoggle">
	<li class="stats"><a data-panel="stats" class="icon-reports"></a></li>
</ul>

<div class="slidepanel pane stats_panel">
	<h4 class="panel-title">Stats</h4>
	<!-- Top 3 Donors -->
	<div class="module first">
		<div class="panel-group">
			<h5>Your Top 3 Donors</h5>
			<ul class="top_donors">
				<li data-ng-repeat="donor in donationsService.stats.top_donors" class="value">
					{{donor.name ? donor.name : '<?= Donation::ANONYMOUS_NAME ?>'}}
				</li>
			</ul>
		</div>
	</div>
	<!-- Total Donation Amount -->
	<div class="module">
		<div class="panel-group">
			<h5>Total Donation Amount</h5>
			<span class="total_amountRaised value highlight large">
				${{number_format(donationsService.stats.total_amountRaised)}}
			</span>
		</div>
	</div>
	<!-- Top Donation Amount -->
	<div ng-hide="stats.top_donation == null">
		<div class="module">
			<div class="panel-group">
				<h5>Top Donation Amount</h5>
				<span class="top_donation value highlight large">
					${{number_format(donationsService.stats.top_donation.amount)}} ({{donationsService.stats.top_donation.displayName}})
				</span>
			</div>
		</div>
	</div>
	<!-- Number of Donations -->
	<div class="module">
		<div class="panel-group">
			<h5>Number Of Donations</h5>
			<span class="number_of_donations value large">
				{{donationsService.stats.number_of_donations}}
			</span>
		</div>
	</div>
	<!-- Number of Donors -->
	<div class="module">
		<div class="panel-group">
			<h5>Number Of Donors</h5>
			<span class="number_of_donors value large">
				{{donationsService.stats.number_of_donors}}
			</span>
		</div>
	</div>
</div>