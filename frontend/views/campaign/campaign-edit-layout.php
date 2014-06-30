<?
use common\models\Campaign;
use yii\helpers\Html;
use common\widgets\file\ImageInput;
?>
<div id="collapseTwo" data-campaign-layoutType="<?= str_replace(' ', '', $campaign->layoutType) ?>">

    <div class="form-group field-campaign-layoutType <?= ($campaign->hasErrors('type')) ? 'has-error' : '' ?>">
        <?= Html::activeDropDownList($campaign, 'layoutType', array_combine(Campaign::$LAYOUT_TYPES, Campaign::$LAYOUT_TYPES), ['class' => 'form-control select-block']) ?>
        <i class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="left" title="Some tooltip here."></i>
        <?= Html::error($campaign, 'layoutType', ['class' => 'help-block']) ?>
    </div>
    <?= $form->field($campaign, 'channelName', ['autoPlaceholder' => true]); ?>
    <?= $form->field($campaign, 'channelTeam', ['autoPlaceholder' => true]); ?>
    <div id="campaign-multichannels">
        <? if ($campaign->isNewRecord): ?>
            <div class="label label-danger">Save campaign before adding channels</div>
        <? endif ?>
        <input type="text" id="addLayoutTeam" placeholder="Add channel(s)"> <a class="btn btn-success btn-xs" onclick="addNewLayoutTeam()"> <i class="icon icon-add2"></i></a>
        <div id="layoutTeams">

        </div>
    </div>
     <?= $form->field($campaign, 'themeId')->hiddenInput()->label(null, ['style' => 'display:none'])?>
    <div class='theme-name <? if (!$campaign->themeId) { echo 'hidden';} ?>'>
        <label>
            Selected theme:
        </label>
        <span><?= $campaign->theme?$campaign->theme->name:''?></span>
    </div>
    <button type="button" class="btn btn-primary" onclick="layoutChooseTheme()">Choose a theme</button> <i class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="left" title="Choose how you want to style your layout page. Options based on the Type of Layout you selected and which plan you're on."></i>

    <br />
    <br />

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
    <?= $form->field($campaign, 'primaryColor')->label('Choose a primary color:')->input('color'); ?>
    <?= $form->field($campaign, 'secondaryColor')->label('Choose a secondary color:')->input('color'); ?>

</div>