<?
use frontend\models\streamboard\WidgetCampaignBarTimer;

?>
<div ng-if="region.widgetCampaignBar.timerEnable" ng-init="module = region.widgetCampaignBar.timerModule"
     draggable-widget="region.widgetCampaignBar.timerModule"
     draggable-region="region"
     draggable-fields="{widgetLeftAttribute:'positionX', widgetTopAttribute:'positionY'}"
     draggable-config="{containment:'parent'}"
     class='campaign-timers'
     interaction
     draggable>
    <div>
        <div ng-if="module.timerType == '<?= WidgetCampaignBarTimer::TIMER_TYPE_LOCALTIME ?>' ">
            Current time: <span current-time format="h:mma"></span>
        </div>
        <div ng-if="module.timerType == '<?= WidgetCampaignBarTimer::TIMER_TYPE_COUNTDOWN ?>' ">
            <div ng-show="currentTimestamp>module.countDownFromTimestamp || !module.countDownFrom">
                <timer end-time="module.countDownTo">
                    <?= $this->render('timer') ?>
                </timer>
            </div>
        </div>
        <div ng-if="module.timerType == '<?= WidgetCampaignBarTimer::TIMER_TYPE_COUNT_UP ?>' ">
            <div ng-show="module.countUpStatus">
                <timer start-time="module.countUpStartTime">
                    <?= $this->render('timer') ?>
                </timer>
            </div>
            <div ng-if="!module.countUpStatus">
                <div count-up-timer>
                </div>
            </div>
        </div>
    </div>
</div>
