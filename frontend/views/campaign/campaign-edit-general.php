<?
use common\models\Campaign;
use yii\helpers\Html;
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                General Settings
            </a>
        </div>
    </div>
    <div class="panel-collapse collapse in" id="collapseOne">
        <?= $form->field($campaign, 'name', ['autoPlaceholder' => true]); ?>
        <div class="form-group field-layout-domain <?= ($campaign->hasErrors('domain')) ? 'has-error' : '' ?>">
            <?= Html::activeInput('text', $campaign, 'domain', ['class' => 'form-control', 'placeholder' => 'Google Analytics Tracking ID']) ?>
            <i class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="left" title="Add Google Analytics to your page by entering the Tracking ID you want to use with this layout. If you don't want to use this feauter, leave this blank."></i>
            <?= Html::error($campaign, 'domain', ['class' => 'help-block']) ?>
        </div>

        <div class="form-group field-layout-streamService <?= ($campaign->hasErrors('streamService')) ? 'has-error' : '' ?>">
            <?= Html::activeDropDownList($campaign, 'streamService', array_combine(Campaign::$STREAM_SERVICES, Campaign::$STREAM_SERVICES), ['class' => 'form-control']) ?>
            <i class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="left" title="Select the streaming service your want to pull from."></i>
            <?= Html::error($campaign, 'streamService', ['class' => 'help-block']) ?>
        </div>
    </div>
</div>