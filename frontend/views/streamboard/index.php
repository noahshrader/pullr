<?

use yii\helpers\Html;
use yii\helpers\Url;
//use frontend\assets\StreamboardAsset;

//$this->registerJsFile('@web/js/campaign/edit.js', common\assets\CommonAsset::className());

//StreamboardAsset::register($this);
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
		<div class="tab-pane active" id="donations" data-ng-controller="DonationsController"
            data-ng-init="start_time = <?php echo time(); ?>;
            csrf_token_name = '<?php echo $csrf_token_name;?>';
            csrf_token = '<?php echo $csrf_token;?>';
            ">

            <input type="button" data-ng-click="addDonation()" />
            <ul class="donations_list">
               <li data-ng-repeat="donation in donations">
                   {{donation.name}} - {{donation.amount}}
               </li>
            </ul>

			<ul class="bottom-panel-nav two-tabs cf">
				<li><a data-panel="campaigns_list">Campaigns</a></li>
				<li><a data-panel="stats">Stats</a></li>
			</ul>
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
        <div class="campaigns_list_panel slidepanel">
            <div class="campaigns_list">
                <h3>Campaigns</h3>
                <?php
                    foreach($campaigns as $campaign) {
                ?>
                    <input type="checkbox" name="campaigns[]"
                        value="<?php echo $campaign['id']; ?>"
                        id="campaign_<?php echo $campaign['id']; ?>"
                        data-ng-model="campaigns.<?php echo $campaign['id']; ?>"


                        />
                    <label for="campaign_<?php echo $campaign['id']; ?>">
                        <?php echo $campaign['name']; ?>
                    </label>
                <?php
                    }
                ?>

            </div>
            <a class="close icon-cross"></a>
        </div>
        <div class="stats_panel slidepanel">
            <a class="close icon-cross"></a>
        </div>
    </div>
</section>

</div>


<script src="/js/streamboard/angular/angular.min.js"></script>
<script src="/js/streamboard/streamboard.js"></script>
