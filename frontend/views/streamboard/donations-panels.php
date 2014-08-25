<?php
		use common\models\Donation;
?>
<!-- Stats Panel Link -->
<ul class="panel-nav paneltoggle">
	<li class="stats"><a data-panel="stats"><i class="icon icon-bargraph"></i>Stats</a></li>
</ul>

<div class="stats_panel slidepanel">
	<h3>Stats</h3>
	<!-- Top 3 Donors -->
	<h4>Your Top 3 Donors</h4>
	<ul class="top_donors">
		<li data-ng-repeat="donor in stats.top_donors">
			{{donor.name ? donor.name : '<?= Donation::ANONYMOUS_NAME ?>'}}
		</li>
	</ul>
	<!-- Total Donation Amount -->
	<h4>Total Donation Amount</h4>
	<span class="total_amountRaised" >
		${{number_format(stats.total_amountRaised)}}
	</span>
	<!-- Top Donation Amount -->
	<div ng-hide="stats.top_donation == null">
		<h4>Top Donation Amount</h4>
		<span class="top_donation" >
			${{number_format(stats.top_donation.amount)}} ({{stats.top_donation.displayName}})
		</span>
	</div>
	<!-- Number of Donations -->
	<h4>Number Of Donations</h4>
	<span class="number_of_donations" >
		{{stats.number_of_donations}}
	</span>
	<!-- Number of Donors -->
	<h4>Number Of Donors</h4>
	<span class="number_of_donors" >
		{{stats.number_of_donors}}
	</span>

	<a class="close icon-cross"></a>
</div>