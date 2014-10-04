<?
use frontend\models\streamboard\WidgetCampaignBarTimer;

?>
<div ng-if="region.widgetCampaignBar.timerEnable" child-scope ng-init="module = region.widgetCampaignBar.timerModule">
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
                <div ng-init="time = getPausedTimestamp(module)">
                    <timer autostart="false" countdown="time">
                        <?= $this->render('timer') ?>
                    </timer>
                </div>
            </div>
        </div>
    </div>
</div>
