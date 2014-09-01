<?php
	use common\models\Donation;
?>
<!-- Stats Panel Link -->
<ul class="panel-nav paneltoggle">
	<li class="stats"><a data-panel="stats"><i class="icon icon-reports"></i>Stats</a></li>
</ul>

<div class="slidepanel stats_panel">
	<h4>Stats</h4>
	<!-- Top 3 Donors -->
	<div class="panel-group">
		<h5>Your Top 3 Donors</h5>
		<ul class="top_donors">
			<li data-ng-repeat="donor in stats.top_donors" class="value">
				{{donor.name ? donor.name : '<?= Donation::ANONYMOUS_NAME ?>'}}
			</li>
		</ul>
	</div>
	<!-- Total Donation Amount -->
	<div class="panel-group">
		<h5>Total Donation Amount</h5>
		<span class="total_amountRaised value">
			${{number_format(stats.total_amountRaised)}}
		</span>
	</div>
	<!-- Top Donation Amount -->
	<div class="panel-group">
		<div ng-hide="stats.top_donation == null">
		<h5>Top Donation Amount</h5>
		<span class="top_donation value">
			${{number_format(stats.top_donation.amount)}} ({{stats.top_donation.displayName}})
		</span>
		</div>
	</div>
	<!-- Number of Donations -->
	<div class="panel-group">
		<h5>Number Of Donations</h5>
		<span class="number_of_donations value">
			{{stats.number_of_donations}}
		</span>
	</div>
	<!-- Number of Donors -->
	<div class="panel-group">
		<h5>Number Of Donors</h5>
		<span class="number_of_donors value">
			{{stats.number_of_donors}}
		</span>
	</div>
</div>