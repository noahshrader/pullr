<?

use yii\helpers\Html;
use yii\helpers\Url;
//use frontend\assets\StreamboardAsset;

//$this->registerJsFile('@web/js/campaign/edit.js', common\assets\CommonAsset::className());

//StreamboardAsset::register($this);
?>

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
		<div class="tab-pane active" id="donations">
			<ul class="bottom-panel-nav two-tabs cf">
				<li><a>Campaigns</a></li>
				<li><a>Stats</a></li>
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
	<div class="slidepanel">
		<a class="close icon-cross"></a>
	</div>
</section>
<script src="../js/streamboard.js"></script>
<script src="../js/plugins.js"></script>