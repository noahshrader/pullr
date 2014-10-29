<?
use common\models\Campaign;
use yii\helpers\Html;
use common\widgets\file\ImageInput;
use common\models\Plan;
$user = \Yii::$app->user->identity;

?>
<div id="collapseTwo" data-campaign-layoutType="<?= str_replace(' ', '', $campaign->layoutType) ?>">
    <div class="module">
        <h5 class="module-title"><i class="icon icon-design3"></i>Layout</h5>
        <div class="module-inner">
            <!-- Campaign Page Layout -->
            <div class="form-group field-campaign-layoutType <?= ($campaign->hasErrors('type')) ? 'has-error' : '' ?>">
                <label class="control-label">Layout Type</label>
                <i class="icon icon-help" data-toggle="tooltip" data-placement="right" title="Select <b>Single Stream</b> for a single channel view. <br /> <b>Team Stream</b> pulls in an entire Twitch team.<br /><b>Multi Stream</b> pulls in your own channels."></i>
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
    </div>
    <div class="module last">
        <h5 class="module-title"><i class="icon icon-design2"></i>Theme</h5>
        <div class="module-inner">
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
                <button type="button" class="btn btn-secondary" onclick="layoutChooseTheme()">Choose a theme</button>
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
    		
    		<div class="row theme-color-picker">
    			<div class="col-sm-6">
    		        <!-- Primary Color -->
    		        <div class="colorpicker">
    		            <div class="colorpickerwrap">
    		                <span class="color-choice"></span>
    		                <?= $form->field($campaign, 'primaryColor')->label('Primary Color')->input('text'); ?>
    		            </div>
    		        </div>
    			</div>
    			
    			<div class="col-sm-6">
    		        <!-- Secondary Color -->
    		        <div class="colorpicker">
    		            <div class="colorpickerwrap">
    		                <span class="color-choice"></span>
    		                <?= $form->field($campaign, 'secondaryColor')->label('Secondary Color')->input('text'); ?>
    		            </div>
    		        </div>
    			</div>
    		</div>
        </div>
    </div>
</div>