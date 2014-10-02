<?
use frontend\models\streamboard\WidgetCampaignBarTimer;

?>
<div class="module">
    <div class="form-group timer-module">
        <h5>Type of Timer</h5>
        <div ng-init='TIMER_TYPES=<?= json_encode(WidgetCampaignBarTimer::$TIMER_TYPES) ?>'></div>
        <select ui-select2="{minimumResultsForSearch: -1}" ng-model="module.timerType" ng-change="regionChanged(region)"
                data-placeholder="Select one...">
            <option value=""></option>
            <option ng-repeat='timerType in TIMER_TYPES' value="{{timerType}}">
                {{timerType}}
            </option>
        </select>
        <div ng-show="module.timerType == '<?= WidgetCampaignBarTimer::TIMER_TYPE_COUNTDOWN ?>'">
             <div>
                 <!--countDownFrom-->
                 <div class="dropdown">
                     From <a class="dropdown-toggle" id="dropdown2" role="button" data-toggle="dropdown" data-target="#" href="#">
                         <div class="input-group"><input type="text" class="form-control input-sm" ng-model="module.countDownFrom"><span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                         </div>
                     </a>
                     <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                         <datetimepicker data-ng-model="module.countDownFrom" data-datetimepicker-config="{ dropdownSelector: '#dropdown2' }" on-set-time="onSetTimeCountDownFrom"/>
                     </ul>
                 </div>
                 <div class="dropdown">
                     To <a class="dropdown-toggle" id="dropdown2" role="button" data-toggle="dropdown" data-target="#" href="#">
                         <div class="input-group"><input type="text" class="form-control input-sm" ng-model="module.countDownTo"><span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                         </div>
                     </a>
                     <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                         <datetimepicker data-ng-model="module.countDownTo" data-datetimepicker-config="{ dropdownSelector: '#dropdown2' }" on-set-time="onSetTimeCountDownTo"/>
                     </ul>
                 </div>
             </div>
        </div>
    </div>
</div>
