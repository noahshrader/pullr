<?php
    use common\models\Donation;
?>
<ul class="bottom-panel-nav one-tabs">
        <li><a data-panel="stats"><i class="icon icon-bargraph"></i></a></li>
</ul>


<div class="stats_panel slidepanel">
    <h3>Stats</h3>
    <label>Your Top 3 Donors</label>
    <ul class="top_donors">
        <li data-ng-repeat="donor in stats.top_donors">
            {{donor.name ? donor.name : '<?= Donation::ANONYMOUS_NAME ?>'}}
        </li>
    </ul>
        <label>Total Donation Amount</label>
        <span class="total_amountRaised" >
            ${{number_format(stats.total_amountRaised)}}
        </span>
    <div ng-hide="stats.top_donation == null">
        <label>Top Donation Amount</label>
        <span class="top_donation" >
            ${{number_format(stats.top_donation.amount)}} ({{stats.top_donation.displayName}})
        </span>
    </div>

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