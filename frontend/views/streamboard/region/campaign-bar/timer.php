<?
use frontend\models\streamboard\WidgetCampaignBarTimer;

?>
<div ng-if="region.widgetCampaignBar.timerEnable" child-scope ng-init="module = region.widgetCampaignBar.timerModule">
    <div>
        <div ng-if="module.timerType == '<?= WidgetCampaignBarTimer::TIMER_TYPE_LOCALTIME ?>' ">
            Current time: <span current-time format="h:mma"></span>
        </div>
        <div ng-if="module.timerType == '<?= WidgetCampaignBarTimer::TIMER_TYPE_COUNTDOWN ?>' ">
            <div ng-show="currentTimestamp>module.countDownFromTimestamp">
                <timer end-time="module.countDownTo">
                    <span ng-show="days">{{days}} day{{daysS}},</span>
                    <span ng-show="hours || days">{{hours}} hour{{hoursS}},</span>
                    <span ng-show="minutes || hours || days">{{minutes}} minute{{minutesS}},</span>
                    <span>{{seconds}} second{{secondsS}}.</span>
                </timer>
            </div>
        </div>
    </div>
</div>
