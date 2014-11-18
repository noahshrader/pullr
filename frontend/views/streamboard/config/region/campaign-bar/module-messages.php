<?
$MAX_LENGTH = 100;
?>
<div class="module first">
	<div class="panel-group">
	    <h5>Rotation Speed <span class="slider-value value">{{module.rotationSpeed}} sec</span></h5>
	    <slider ng-model="module.rotationSpeed" floor="1" ceiling="20" step="1"
	            ng-change="regionChanged(region)"></slider>
	</div>
</div>
<div class="module">
	<div class="panel-group">
		<textarea ng-model="module.message1" maxlength="<?=$MAX_LENGTH?>" placeholder="Custom Message" ng-change="regionChanged(region)"></textarea>
		<textarea ng-model="module.message2" maxlength="<?=$MAX_LENGTH?>" placeholder="Custom Message" ng-change="regionChanged(region)"></textarea>
		<textarea ng-model="module.message3" maxlength="<?=$MAX_LENGTH?>" placeholder="Custom Message" ng-change="regionChanged(region)"></textarea>
		<textarea ng-model="module.message4" maxlength="<?=$MAX_LENGTH?>" placeholder="Custom Message" ng-change="regionChanged(region)"></textarea>
		<textarea ng-model="module.message5" maxlength="<?=$MAX_LENGTH?>" placeholder="Custom Message" ng-change="regionChanged(region)"></textarea>
	</div>
</div>
