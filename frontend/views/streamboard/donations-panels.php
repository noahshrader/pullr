<ul class="bottom-panel-nav two-tabs cf">
        <li><a data-panel="campaigns_list">Campaigns</a></li>
        <li><a data-panel="stats">Stats</a></li>
</ul>
<div class="campaigns_list_panel slidepanel">
    <div class="campaigns_list">
        <h3>Campaigns</h3>
        <div data-ng-repeat="campaign in campaigns">
            <label>
                <input type="checkbox" data-ng-model="campaign.streamboardSelected">
                {{campaign.name}}
            </label>
            <br>
        </div>
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