<?
use frontend\models\streamboard\WidgetCampaignBarTimer;

?>
<div class="module">
    <div class="form-group">
        <h5>Type of Timer</h5>
        <div ng-init='TIMER_TYPES=<?= json_encode(WidgetCampaignBarTimer::$TIMER_TYPES) ?>'></div>
        <select ui-select2="{minimumResultsForSearch: -1}" ng-model="module.timerType" ng-change="regionChanged(region)"
                data-placeholder="Select one...">
            <option value=""></option>
            <option ng-repeat='timerType in TIMER_TYPES' value="{{timerType}}">
                {{timerType}}
            </option>
        </select>
    </div>
</div>
