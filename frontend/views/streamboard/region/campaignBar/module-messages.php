<?
$MAX_LENGTH = 100;
?>
<h4>Messages</h4>
<textarea ng-model="model.message1" maxlength="<?=$MAX_LENGTH?>" placeholder="Custom Message" ng-change="regionChanged(region)"></textarea>
<textarea ng-model="model.message2" maxlength="<?=$MAX_LENGTH?>" placeholder="Custom Message" ng-change="regionChanged(region)"></textarea>
<textarea ng-model="model.message3" maxlength="<?=$MAX_LENGTH?>" placeholder="Custom Message" ng-change="regionChanged(region)"></textarea>
<textarea ng-model="model.message4" maxlength="<?=$MAX_LENGTH?>" placeholder="Custom Message" ng-change="regionChanged(region)"></textarea>
<textarea ng-model="model.message5" maxlength="<?=$MAX_LENGTH?>" placeholder="Custom Message" ng-change="regionChanged(region)"></textarea>
<div class="form-group">
    <label>Rotation Speed</label>
    <slider ng-model="module.rotationSpeed" floor="1" ceiling="20" step="1"
            ng-change="regionChanged(region)"></slider>
    <span>{{module.rotationSpeed}} sec</span>
</div>
