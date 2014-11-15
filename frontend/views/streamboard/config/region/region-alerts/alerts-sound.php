<?
use frontend\models\streamboard\WidgetAlertsPreference;
?>
<div class="panel-group media-item cf" ng-class="{selected: file==preference.sound && libraryType==preference.soundType}" ng-click="selectSound(preference, file, libraryType, region)">
    <div class="mediaActions">
        <i class="icon mdi-av-play-circle-fill" ng-click="alertMediaManagerService.playSound(file, libraryType, preference.volume)"></i>
        <i class="icon mdi-navigation-close" ng-show='libraryType == <?= json_encode(WidgetAlertsPreference::FILE_TYPE_CUSTOM) ?> ' ng-click="alertMediaManagerService.removeSound(file)"></i>
    </div>
    <span>{{file | fileName | replace: '-' : ' '}}</span>
</div>
