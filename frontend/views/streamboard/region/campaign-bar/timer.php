<?
use frontend\models\streamboard\WidgetCampaignBarTimer;

?>
<div ng-if="region.widgetCampaignBar.timerEnable" child-scope ng-init="module = region.widgetCampaignBar.timerModule">
    <div >
        <div ng-if="module.timerType == '<?= WidgetCampaignBarTimer::TIMER_TYPE_LOCALTIME ?>' ">
            YYYY
        </div>
    </div>
</div>
