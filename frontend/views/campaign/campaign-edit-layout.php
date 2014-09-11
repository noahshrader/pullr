<?
use common\models\Campaign;
use yii\helpers\Html;
use common\widgets\file\ImageInput;
use common\models\Plan;
$user = \Yii::$app->user->identity;

?>
<div id="collapseTwo" data-campaign-layoutType="<?= str_replace(' ', '', $campaign->layoutType) ?>">
    <h3>Campaign Page Settings</h3>
    <!-- Campaign Page Layout -->
    <div class="form-group field-campaign-layoutType <?= ($campaign->hasErrors('type')) ? 'has-error' : '' ?>">
        <label class="control-label">Layout Type</label>
        <i class="icon-help" data-toggle="tooltip" data-placement="bottom" title="Some tooltip here."></i>
        <?= Html::error($campaign, 'layoutType', ['class' => 'help-block']) ?>
        <?= Html::activeDropDownList($campaign, 'layoutType', array_combine(Campaign::$LAYOUT_TYPES, Campaign::$LAYOUT_TYPES), ['class' => 'select-block']) ?>
    </div>

    <!-- if Single Channel -->
    <div id="campaign-channelname" class="form-group highlight-wrap">
        <?= $form->field($campaign, 'channelName', ['autoPlaceholder' => false])->label("Channel Name"); ?>
    </div>

    <!-- if Team Channel -->
    <div id="campaign-channelteam" class="form-group highlight-wrap">
        <?= $form->field($campaign, 'channelTeam', ['autoPlaceholder' => false])->label("Team Channel Name"); ?>
    </div>

    <!-- if Multichannel -->
    <div id="campaign-multichannels" class="form-group highlight-wrap">
        <? if ($campaign->isNewRecord): ?>
            <div class="label label-danger">Save campaign before adding channels</div>
        <? endif ?>
        <input type="text" class="form-control" id="addLayoutTeam" placeholder="Add channel(s)"> <a class="btn btn-default btn-xs" onclick="addNewLayoutTeam()"> <i class="icon icon-add2"></i></a>
        <div id="layoutTeams"></div>
    </div>

    <!-- Choose a Theme -->
    <div class="form-group">
        <?= $form->field($campaign, 'themeId')->hiddenInput()->label(null, ['style' => 'display:none'])?>
    </div>
    <div class='theme-name <? if (!$campaign->themeId) { echo 'hidden';} ?>'>
        <label>
            Selected theme:
        </label>
        <span><?= $campaign->theme?$campaign->theme->name:''?></span>
    </div>
    <button type="button" class="btn btn-primary" onclick="layoutChooseTheme()">Choose a theme</button>
    <i class="icon-help" data-toggle="tooltip" data-placement="top" title="Choose how you want to style your layout page. Options based on the Type of Layout you selected and which plan you're on."></i>
    <!-- Background Image Upload (if Pro) -->
    <? if ($user->plan == Plan::PLAN_PRO): ?>
    <div id="logo-container">
        <img class="logo" src="<?= $campaign->backgroundImageSmallUrl ?>">
        <div class="form-group user-images <?= $campaign->hasErrors('backgroundImage') ? 'has-error' : '' ?>">
            <label class="control-label">Upload a background image</label> 
                <?=ImageInput::widget(['name' => 'backgroundImage']);?>
            <? if ($campaign->hasErrors('backgroundImage')): ?>
                <?= Html::error($campaign, 'backgroundImage', ['class' => 'help-block']); ?>
            <? endif ?>
        </div>
    </div>
    <? endif ?>
    <!-- Primary Color -->
    <div class="form-group colorpicker">
        <?= $form->field($campaign, 'primaryColor')->label('Choose a primary color:')->input('hidden'); ?>
        <span class="color-choice"></span>
    </div>
    <!-- Secondary Color -->
    <div class="form-group colorpicker">
        <?= $form->field($campaign, 'secondaryColor')->label('Choose a secondary color:')->input('hidden'); ?>
        <span class="color-choice"></span>
    </div>
</div>