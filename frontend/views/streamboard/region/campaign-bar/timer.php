<?
use frontend\models\streamboard\WidgetCampaignBarTimer;

?>
<div ng-if="region.widgetCampaignBar.timerEnable" child-scope ng-init="module = region.widgetCampaignBar.timerModule">
    <div >
        <div ng-if="module.timerType == '<?= WidgetCampaignBarTimer::TIMER_TYPE_LOCALTIME ?>' ">
            Current time: <span current-time format="h:mma"></span>
        </div>
        <div ng-if="module.timerType == '<?= WidgetCampaignBarTimer::TIMER_TYPE_COUNTDOWN ?>' ">
            text {{module.countDownTo}}
            <timer>{{module.countDownTo}}</timer>
        </div>
    </div>
</div>
