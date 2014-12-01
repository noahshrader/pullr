<?
use common\models\Campaign;
use yii\helpers\Html;
use common\widgets\file\ImageInput;
use common\models\Plan;
$user = \Yii::$app->user->identity;

?>
<div id="collapseTwo" data-campaign-layoutType="<?= str_replace(' ', '', $campaign->layoutType) ?>">
    <div class="module-inner">
        <h5><i class="icon mdi-action-view-quilt"></i>Layout</h5>
        <!-- Campaign Page Layout -->
        <div class="form-group field-campaign-layoutType <?= ($campaign->hasErrors('type')) ? 'has-error' : '' ?>">
            <label class="control-label">Layout Type</label>
            <i class="icon mdi-action-help" data-toggle="tooltip" data-placement="right" title="Select <b>Single Stream</b> for a single channel view. <br /> <b>Team Stream</b> pulls in an entire Twitch team.<br /><b>Multi Stream</b> pulls in your own channels."></i>
            <?= Html::error($campaign, 'layoutType', ['class' => 'help-block']) ?>
            <?= Html::activeDropDownList($campaign, 'layoutType', array_combine(Campaign::$LAYOUT_TYPES, Campaign::$LAYOUT_TYPES), ['class' => 'select-block', 'onchange' => 'getDefaultTheme()']) ?>
        </div>

        <!-- if Single Channel -->
        <div id="campaign-channelname" class="form-group highlight-wrap float">
            <?= $form->field($campaign, 'channelName', ['autoPlaceholder' => false])->input('text', ['value' => $campaign->isNewRecord ? \Yii::$app->user->identity->uniqueName : $campaign->channelName])->label("Channel Name")->textInput(array('placeholder' => 'Enter a channel name')); ?>
        </div>

        <!-- if Team Channel -->
        <div id="campaign-channelteam" class="form-group highlight-wrap">
            <?= $form->field($campaign, 'channelTeam', ['autoPlaceholder' => false])->label("Twitch Team Channel Name")->textInput(array('placeholder' => 'Enter a Twitch team channel')); ?>
        </div>

        <!-- if Multichannel -->
        <div id="campaign-multichannels" class="form-group highlight-wrap">
            <? if ($campaign->isNewRecord): ?>
                <div class="label label-danger">Save campaign before adding channels</div>
            <? endif ?>
            <div class="combined-form-wrap">
                <input type="text" class="form-control" id="addLayoutTeam" placeholder="Add a channel">
                <a onclick="addNewLayoutTeam()" class="icon mdi-content-add-circle"></a>
            </div>
            <div id="layoutTeams" class="team-list"></div>
        </div>
    </div>
    <div class="module-inner">
        <h5><i class="icon mdi-image-palette"></i>Design</h5>
        <!-- Choose a Theme -->
        <div class="hide">
            <?= $form->field($campaign, 'themeId')->hiddenInput()->label(null, ['style' => 'display:none'])?>
        </div>
        <div class="form-group theme-selection cf">
            <button type="button" class="btn btn-default" onclick="layoutChooseTheme()">Select a theme</button>
            <div class='selected-theme theme-name <? if (!$campaign->themeId) { echo 'hidden';} ?>'>
                <label>Current Theme:</label>
                <span><?= $campaign->theme?$campaign->theme->name:''?></span>
            </div>
        </div>
        <div class="theme-color-picker form-group cf">
            <div class="col-md-6">
                <!-- Primary Color -->
                <div class="colorpicker">
                    <div class="colorpickerwrap">
                        <span class="color-choice"></span>
                        <?= $form->field($campaign, 'primaryColor')->label('Primary Color')->input('text'); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Secondary Color -->
                <div class="colorpicker">
                    <div class="colorpickerwrap">
                        <span class="color-choice"></span>
                        <?= $form->field($campaign, 'secondaryColor')->label('Secondary Color')->input('text'); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Background Image -->
        <div class="form-group cf">
            <div id="logo-container">
                <? if (!empty($campaign->backgroundImageSmallUrl)):?>
                <div class="image-upload-preview">
                    <div class="close fileinput-remove">
                        <a id="image-uploaded" data-campaignid="<?=$campaign->id;?>"></a>
                    </div>
                    <img src="<?= $campaign->backgroundImageSmallUrl ?>">
                </div>
                <?endif;?>
                <div class="form-group user-images <?= $campaign->hasErrors('backgroundImage') ? 'has-error' : '' ?>">
                    <label class="control-label">Background Image</label>
                    <i class="icon mdi-action-help" data-toggle="tooltip" data-placement="right" title="Add a background image to your campaign page.<br/><strong>Max file size is 1MB.</strong>"></i>
                    <?=ImageInput::widget(['name' => 'backgroundImage']);?>
                    <? if ($campaign->hasErrors('backgroundImage')): ?>
                        <?= Html::error($campaign, 'backgroundImage', ['class' => 'help-block']); ?>
                    <? endif ?>
                </div>
            </div>
        </div>
    </div>
</div>