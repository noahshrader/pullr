<?
use yii\helpers\Url;
use yii\web\View;

/*@var $this View*/
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
            <?= $this->render('donations') ?>
            <!-- Accordion slide two (Region 1) -->
            <div class="tab-pane" id="design">
                    <h3>Region 1</h3>
            </div>
            <!-- Accordion slide three (Region 2) -->
            <div class="tab-pane" id="region_1">
                    <h3>Region 2</h3>
            </div>
    </div>
</section>

</div>
