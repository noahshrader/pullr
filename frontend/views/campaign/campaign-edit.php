<?

use common\models\Campaign;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use common\widgets\file\ImageInput;

$this->registerJsFile('@web/js/campaign/edit.js', common\assets\CommonAsset::className());
$user = \Yii::$app->user->identity;
?>

    <? if ($campaign): ?>

    <?
    $events = $user->events;
    $eventsNames = [];
    $eventsIds = [];
    foreach ($events as $event) {
        $eventsNames[] = $event->name;
        $eventsIds[] = $event->id;
    }
    ?>
    <div id="campaignEdit" class="layout-edit" data-campaignType="<?= htmlspecialchars($campaign->type) ?>" data-id="<?= $campaign->id ?>">
        <h4> <?= ($campaign->name)?$campaign->name:'New campaign' ?></h4>
        <? $form = ActiveForm::begin(['options' => [
                            'enctype' => 'multipart/form-data', 'method' => 'POST']]) ?>
        <div class="panel-group" id="accordion">
             <?= $this->render('campaign-edit-general', [
                            'form' => $form,
                            'campaign' => $campaign
                        ]); ?>    
            <div class="panel panel-default">
                <div class="panel-heading"><div class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                            Layout options
                        </a>
                </div></div>
                <div id="collapseTwo" class="panel-collapse collapse<?= $campaign->chat ? ' chatOn' : '' ?><?= $campaign->enableDonations ? ' enableDonations' : '' ?>" data-campaign-layoutType="<?= str_replace(' ', '', $campaign->layoutType) ?>">
                    <div class="form-group field-campaign-layoutType <?= ($campaign->hasErrors('type')) ? 'has-error' : '' ?>">
                        <?= Html::activeDropDownList($campaign, 'layoutType', array_combine(Campaign::$LAYOUT_TYPES, Campaign::$LAYOUT_TYPES), ['class' => 'form-control']) ?>
                        <i class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="left" title="Some tooltip here."></i>
                        <?= Html::error($campaign, 'layoutType', ['class' => 'help-block']) ?>
                    </div>
                    <?= $form->field($campaign, 'channelName', ['autoPlaceholder' => true]); ?>
                    <?= $form->field($campaign, 'channelTeam', ['autoPlaceholder' => true]); ?>
                    <div id="layout-multichannels">
                        <? if ($campaign->isNewRecord): ?>
                            <div class="label label-danger">Save layout before adding channels</div>
                        <? endif ?>
                        <input type="text" id="addLayoutTeam" placeholder="Add channel(s)"> <a class="btn btn-success btn-xs" onclick="addNewLayoutTeam()"> <i class="glyphicon glyphicon-plus"></i></a>
                        <div id="layoutTeams">

                        </div>
                    </div>
                    <?= $form->field($campaign, 'chat')->label('Chat on?')->checkbox([], false); ?>
                    <?= $form->field($campaign, 'chatToggle')->label('Use toggle?')->checkbox([], false); ?>
                    <?= $form->field($campaign, 'enableDonations')->label('Enable donations?')->checkbox([], false); ?>
                    <?= $form->field($campaign, 'eventId', ['autoPlaceholder' => true])->label('Select an Event')->dropDownList(array_combine($eventsIds, $eventsNames)) ?>

                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading"><div class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                            Customizations
                        </a>
                </div></div>
                <div class="panel-collapse collapse" id="collapseThree">
                    <?= $form->field($campaign, 'themeId')->hiddenInput()->label(null, ['style' => 'display:none'])?>
                    <div class='theme-name <? if (!$campaign->themeId) { echo 'hidden';} ?>'>
                        <label>
                            Selected theme:
                        </label>
                        <span><?= $campaign->theme?$campaign->theme->name:''?></span>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="layoutChooseTheme()">Choose a theme</button> <i class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="left" title="Choose how you want to style your layout page. Options based on the Type of Layout you selected and which plan you're on."></i>
                    <img class="logo" src="<?= $campaign->smallPhoto ?>">
                    <div class="form-group user-images <?= $campaign->hasErrors('images') ? 'has-error' : '' ?>">
                        <label class="control-label">Upload a logo</label> 
                            <?=ImageInput::widget();?>
                         <? if ($campaign->hasErrors('images')): ?>
                            <?= Html::error($campaign, 'images', ['class' => 'help-block']); ?>
                         <? endif ?>
                    </div>
                    <?= $form->field($campaign, 'primaryColor')->label('Choose a primary color:')->input('color'); ?>
                    <?= $form->field($campaign, 'secondaryColor')->label('Choose a secondary color:')->input('color'); ?>
                    <?= $form->field($campaign, 'tertiaryColor')->label('Choose a tertiary color:')->input('color'); ?>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading"><div class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
                        Social Integration
                    </a>
                </div></div>
                <div id="collapseFour" class="panel-collapse collapse <?= ($campaign->twitterEnable? 'twitterEnable ': '').($campaign->youtubeEnable? 'youtubeEnable ': '').($campaign->facebookEnable? 'facebookEnable ': '') ?>">
                    <div class="form-group field-layout-streamService <?= ($campaign->hasErrors('streamService')) ? 'has-error' : '' ?>">
            <?= Html::activeDropDownList($campaign, 'streamService', array_combine(Campaign::$STREAM_SERVICES, Campaign::$STREAM_SERVICES), ['class' => 'form-control']) ?>
            <i class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="left" title="Select the streaming service your want to pull from."></i>
            <?= Html::error($campaign, 'streamService', ['class' => 'help-block']) ?>
        </div>
                            <?= $form->field($campaign, 'twitterEnable')->label('Enable Twitter Link?')->checkbox([], false); ?>
                    <?= $form->field($campaign, 'twitterName', ['autoPlaceholder' => true]); ?>
                    <?= $form->field($campaign, 'youtubeEnable')->label('Enable Youtube?')->checkbox([], false); ?>
                    <?= $form->field($campaign, 'youtubeUrl', ['autoPlaceholder' => true]); ?>
                    <?= $form->field($campaign, 'includeYoutubeFeed')->label('Include Youtube Feed?')->checkbox([], false); ?>
                    <?= $form->field($campaign, 'facebookEnable')->label('Enable Facebook?')->checkbox([], false); ?>
                    <?= $form->field($campaign, 'facebookUrl', ['autoPlaceholder' => true]); ?>
                </div>
            </div>
        </div>

        <button class="btn btn-primary">Save</button>
        <? ActiveForm::end() ?>

    </div>
<? else: ?>
<? endif ?> 
<!-- Modal -->
<div class="modal fade" id="modalThemes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>

<div id='modal-social-link' class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            
        </div>
    </div>
</div>