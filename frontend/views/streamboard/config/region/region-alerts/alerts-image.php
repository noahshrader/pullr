<?
use frontend\models\streamboard\WidgetAlertsPreference;
?>
<div class="panel-group media-item images cf" ng-class="{selected: file==preference.image && libraryType==preference.imageType}" ng-click="selectImage(preference, file, libraryType, region)">
	<img class='alert-image-preview' ng-src="{{alertMediaManagerService.getImageUrl(file,libraryType)}}">
    <div class='mediaActions'>
        <i class="glyphicon glyphicon-remove" ng-show='libraryType == <?= json_encode(WidgetAlertsPreference::FILE_TYPE_CUSTOM) ?> ' ng-click="alertMediaManagerService.removeImage(file)"></i>
    </div>
</div>
