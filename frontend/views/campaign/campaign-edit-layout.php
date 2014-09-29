<?
use common\models\Campaign;
use yii\helpers\Html;
use common\widgets\file\ImageInput;
use common\models\Plan;
$user = \Yii::$app->user->identity;

?>
<div id="collapseTwo" data-campaign-layoutType="<?= str_replace(' ', '', $campaign->layoutType) ?>">
    <div class="module">

        <h4>Layout</h4>
        
        <!-- Campaign Page Layout -->
        <div class="form-group field-campaign-layoutType <?= ($campaign->hasErrors('type')) ? 'has-error' : '' ?>">
            <label class="control-label">Layout Type</label>
            <i class="icon icon-help" data-toggle="tooltip" data-placement="bottom" title="Some tooltip here."></i>
            <?= Html::error($campaign, 'layoutType', ['class' => 'help-block']) ?>
            <?= Html::activeDropDownList($campaign, 'layoutType', array_combine(Campaign::$LAYOUT_TYPES, Campaign::$LAYOUT_TYPES), ['class' => 'select-block']) ?>
        </div>

        <!-- if Single Channel -->
        <div id="campaign-channelname" class="form-group highlight-wrap">
            <?= $form->field($campaign, 'channelName', ['autoPlaceholder' => false])->label("Channel Name"); ?>
        </div>

        <!-- if Team Channel -->
        <div id="campaign-channelteam" class="form-group highlight-wrap">
            <?= $form->field($campaign, 'channelTeam', ['autoPlaceholder' => false])->label("Twitch Team Channel Name"); ?>
        </div>

        <!-- if Multichannel -->
        <div id="campaign-multichannels" class="form-group highlight-wrap">
            <? if ($campaign->isNewRecord): ?>
                <div class="label label-danger">Save campaign before adding channels</div>
            <? endif ?>
            <div class="combined-form-wrap">
                <input type="text" class="form-control" id="addLayoutTeam" placeholder="Add channel(s)">
                <a onclick="addNewLayoutTeam()" class="icon icon-plus"></a>
            </div>
            <div id="layoutTeams" class="team-list"></div>
        </div>
    </div>
    <div class="module">

        <h4>Theme</h4>

        <!-- Choose a Theme -->
        <div class="hide">
            <?= $form->field($campaign, 'themeId')->hiddenInput()->label(null, ['style' => 'display:none'])?>
        </div>
        <div class="form-group cf">
            <div class='theme-name <? if (!$campaign->themeId) { echo 'hidden';} ?>'>
                <label>
                    Selected theme:
                </label>
                <span><?= $campaign->theme?$campaign->theme->name:''?></span>
            </div>
            <button type="button" class="btn btn-primary" onclick="layoutChooseTheme()">Choose a theme</button>
            <i class="icon icon-help" data-toggle="tooltip" data-placement="top" title="Choose how you want to style your layout page. Options based on the Type of Layout you selected and which plan you're on."></i>
            <!-- Background Image Upload (if Pro) -->
            <? if ($user->plan == Plan::PLAN_PRO): ?>
            <div id="logo-container" style="display:none;">
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
        </div>

        <!-- Primary Color -->
        <div class="colorpicker">
            <div class="colorpickerwrap">
                <span class="color-choice"></span>
                <?= $form->field($campaign, 'primaryColor')->label('Primary Color')->input('text'); ?>
            </div>
        </div>

        <!-- Secondary Color -->
        <div class="colorpicker">
            <div class="colorpickerwrap">
                <span class="color-choice"></span>
                <?= $form->field($campaign, 'secondaryColor')->label('Secondary Color')->input('text'); ?>
            </div>
        </div>
    </div>
</div>