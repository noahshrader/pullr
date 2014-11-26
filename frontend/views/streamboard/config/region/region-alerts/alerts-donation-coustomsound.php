<?
use yii\helpers\Url;
use frontend\models\streamboard\WidgetAlertsPreference;
/**@var $fileType 'sound'|'image'*/
?>

<div class="tab-content sounds-graphics-content donation-custom-sound">
    <div id="{{baseLink}}-donation-soutomsoudc" class="tab-pane active">
        <div class="files-container">
            <div ng-repeat="item in (customDonationSound.rangeData[region.regionNumber])">                           
                <div class="panel-group media-item cf">
                    <div class="mediaActions">
                        <span>{{item.name | fileName | replace: '-' : ' '}}</span>    
                        <i class="icon mdi-av-play-circle-fill" ng-click="alertMediaManagerService.playSound(item.name, null, preference.volume)"></i>
                    </div>
                    <span>${{item.amount}}</span>
                </div>
            </div>
        </div>
    </div>
</div>