<?
use frontend\models\streamboard\WidgetCampaignBarTimer;

?>
<div class="campaign-timers module first">
    <div class="panel-group">
        <h5>Timer Type</h5>
        <div ng-init='TIMER_TYPES=<?= json_encode(WidgetCampaignBarTimer::$TIMER_TYPES) ?>'></div>
        <select class="timerType" ui-select2="{minimumResultsForSearch: -1}" ng-model="module.timerType" ng-change="regionChanged(region)" data-placeholder="Select one...">
            <option value=""></option>
            <option ng-repeat='timerType in TIMER_TYPES' value="{{timerType}}">
                {{timerType}}
            </option>
        </select>
        <div ng-show="module.timerType == '<?= WidgetCampaignBarTimer::TIMER_TYPE_COUNTDOWN ?>'">
             <div>
                <!--countDownFrom begin-->
                <div class="dropdown module">
                    <h5>From</h5>
                    <a class="dropdown-toggle" id="dateTimeDropdown1" role="button" data-toggle="dropdown" data-target="#" href="#">
                        <input type="text" class="form-control" ng-model="module.countDownFrom">
                        <i class="glyphicon glyphicon-calendar"></i>
                     </a>
                     <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel1">
                         <datetimepicker data-ng-model="module.countDownFrom" data-datetimepicker-config="{ dropdownSelector: '#dateTimeDropdown1' }" on-set-time="onSetTimeCountDownFrom"/>
                     </ul>
                </div>
                <!--countDownFrom end-->
                <!--countDownTo begin-->
                <div class="dropdown module">
                    <h5>To</h5>
                    <a class="dropdown-toggle" id="dateTimeDropdown2" role="button" data-toggle="dropdown" data-target="#" href="#">
                         <input type="text" class="form-control" ng-model="module.countDownTo">
                         <i class="glyphicon glyphicon-calendar"></i>
                     </a>
                     <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel2">
                         <datetimepicker data-ng-model="module.countDownTo" data-datetimepicker-config="{ dropdownSelector: '#dateTimeDropdown2' }" on-set-time="onSetTimeCountDownTo"/>
                     </ul>
                 </div>
                 <!--countDownTo end-->
             </div>
        </div>
        <div class="timer-controls" ng-show="module.timerType == '<?= WidgetCampaignBarTimer::TIMER_TYPE_COUNT_UP ?>'">
            <button class="btn start btn-secondary btn-sm" ng-show="!module.countUpStatus" ng-click="timerCountUpStart(module, region)">Start</button>
            <button class="btn pause btn-secondary btn-sm" ng-show="module.countUpStatus" ng-click="timerCountUpPause(module, region)">Pause</button>
            <button class="btn reset btn-secondary btn-sm" ng-click="timerCountUpReset(module, region)">Reset</button>
        </div>
    </div>
</div>