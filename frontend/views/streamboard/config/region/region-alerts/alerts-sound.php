<?
use frontend\models\streamboard\WidgetAlertsPreference;
?>
<div class="panel-group media-item cf"
     ng-class="{selected: file==preference.sound && libraryType==preference.soundType}"
     ng-click="selectSound(preference, file, libraryType, region)">
    <div class="mediaActions">
        <span class="custom-alert-sound" ng-if="preference.preferenceType =='donations' || (preference.preferenceType == 'campaigns' && preference.includeDonations == true)">
            <input  ng-model="customsounds[file]" class="money-input" type="text" ng-show="moneybuttonclicked==1" ng-blur="moneybuttonclicked=0" focus-on="moneybuttonclicked"
                    ng-change="changeCustomSound(customsounds, key, preference, region)"/>
            <i class="icon mdi-editor-attach-money"  ng-hide="moneybuttonclicked==1" ng-click="moneybuttonclicked=1">{{customsounds[file]}}</i>
        </span>
        <i class="icon mdi-av-play-circle-fill" ng-click="alertMediaManagerService.playSound(file, libraryType, preference.volume)"></i>
        <i class="icon mdi-navigation-close"
           ng-show='libraryType == <?= json_encode(WidgetAlertsPreference::FILE_TYPE_CUSTOM) ?> '
           ng-click="alertMediaManagerService.removeSound(file)"></i>
    </div>
    <span>{{file | fileName | replace: '-' : ' '}}</span>
</div>
