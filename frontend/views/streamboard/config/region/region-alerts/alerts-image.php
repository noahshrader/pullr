<?
use frontend\models\streamboard\WidgetAlertsPreference;
?>
<div class='media-item' ng-class="{selected: file==preference.image && libraryType==preference.imageType}">
    <div>
        <img class='alert-image-preview' ng-src="{{alertMediaManagerService.getImageUrl(file,libraryType)}}">
    </div>
    <span>{{file | fileName | replace: '-' : ' '}}</span>
    <div class='mediaActions'>
        <i class="glyphicon glyphicon-remove" ng-show='libraryType == <?= json_encode(WidgetAlertsPreference::FILE_TYPE_CUSTOM) ?> ' ng-click="alertMediaManagerService.removeImage(file)"></i>
        <button class="btn btn-default btn-xs" ng-click="selectImage(preference, file, libraryType, region)">Select</button>
    </div>
</div>
