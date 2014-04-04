<?

use common\models\Layout;
use kartik\widgets\ActiveForm;
use common\models\Event;
use yii\helpers\Html;
use common\widgets\file\ImageInput;

$this->registerJsFile('@web/js/pullrlayout/edit.js', common\assets\CommonAsset::className());
$user = \Yii::$app->user->identity;
?>

    <? if ($layout): ?>

    <?
    $events = $user->events;
    $eventsNames = [];
    $eventsIds = [];
    foreach ($events as $event) {
        $eventsNames[] = $event->name;
        $eventsIds[] = $event->id;
    }
    ?>
    <div class="layout-edit" data-id="<?= $layout->id ?>">
        <h4> <?= ($layout->name)?$layout->name:'New layout' ?></h4>
        <? $form = ActiveForm::begin(['options' => [
                            'enctype' => 'multipart/form-data', 'method' => 'POST']]) ?>
        <div class="panel-group" id="accordion">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                            General Settings
                        </a>
                    </div>
                </div>
                <div class="panel-collapse collapse in" id="collapseOne">
                    <?= $form->field($layout, 'name', ['autoPlaceholder' => true]); ?>
                    <div class="form-group field-layout-domain <?= ($layout->hasErrors('domain')) ? 'has-error' : '' ?>">
                        <?= Html::activeInput('text', $layout, 'domain', ['class' => 'form-control', 'placeholder' => 'Google Analytics Tracking ID']) ?>
                        <i class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="left" title="Add Google Analytics to your page by entering the Tracking ID you want to use with this layout. If you don't want to use this feauter, leave this blank."></i>
                        <?= Html::error($layout, 'domain', ['class' => 'help-block']) ?>
                    </div>

                    <div class="form-group field-layout-streamService <?= ($layout->hasErrors('streamService')) ? 'has-error' : '' ?>">
                        <?= Html::activeDropDownList($layout, 'streamService', array_combine(Layout::$STREAM_SERVICES, Layout::$STREAM_SERVICES), ['class' => 'form-control']) ?>
                        <i class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="left" title="Select the streaming service your want to pull from."></i>
                        <?= Html::error($layout, 'streamService', ['class' => 'help-block']) ?>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading"><div class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                            Layout options
                        </a>
                </div></div>
                <div id="collapseTwo" class="panel-collapse collapse<?= $layout->chat ? ' chatOn' : '' ?><?= $layout->enableDonations ? ' enableDonations' : '' ?>" data-layout-type="<?= str_replace(' ', '', $layout->type) ?>">
                    <div class="form-group field-layout-type <?= ($layout->hasErrors('type')) ? 'has-error' : '' ?>">
                        <?= Html::activeDropDownList($layout, 'type', array_combine(Layout::$TYPES, Layout::$TYPES), ['class' => 'form-control']) ?>
                        <i class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="left" title="Some tooltip here."></i>
                        <?= Html::error($layout, 'type', ['class' => 'help-block']) ?>
                    </div>
                    <?= $form->field($layout, 'channelName', ['autoPlaceholder' => true]); ?>
                    <?= $form->field($layout, 'channelTeam', ['autoPlaceholder' => true]); ?>
                    <div id="layout-multichannels">
                        <? if ($layout->isNewRecord): ?>
                            <div class="label label-danger">Save layout before adding channels</div>
                        <? endif ?>
                        <input type="text" id="addLayoutTeam" placeholder="Add channel(s)"> <a class="btn btn-success btn-xs" onclick="addNewLayoutTeam()"> <i class="glyphicon glyphicon-plus"></i></a>
                        <div id="layoutTeams">

                        </div>
                    </div>
                    <?= $form->field($layout, 'chat')->label('Chat on?')->checkbox([], false); ?>
                    <?= $form->field($layout, 'chatToggle')->label('Use toggle?')->checkbox([], false); ?>
                    <?= $form->field($layout, 'enableDonations')->label('Enable donations?')->checkbox([], false); ?>
                    <?= $form->field($layout, 'eventId', ['autoPlaceholder' => true])->label('Select an Event')->dropDownList(array_combine($eventsIds, $eventsNames)) ?>

                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading"><div class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                            Customizations
                        </a>
                </div></div>
                <div class="panel-collapse collapse" id="collapseThree">
                    <?= $form->field($layout, 'themeId')->hiddenInput()->label(null, ['style' => 'display:none'])?>
                    <div class='theme-name <? if (!$layout->themeId) { echo 'hidden';} ?>'>
                        <label>
                            Selected theme:
                        </label>
                        <span><?= $layout->theme?$layout->theme->name:''?></span>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="layoutChooseTheme()">Choose a theme</button> <i class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="left" title="Choose how you want to style your layout page. Options based on the Type of Layout you selected and which plan you're on."></i>
                    <img class="logo" src="<?= $layout->smallPhoto ?>">
                    <div class="form-group user-images <?= $layout->hasErrors('images') ? 'has-error' : '' ?>">
                        <label class="control-label">Upload a logo</label> 
                            <?=ImageInput::widget();?>
                         <? if ($layout->hasErrors('images')): ?>
                            <?= Html::error($layout, 'images', ['class' => 'help-block']); ?>
                         <? endif ?>
                    </div>
                    <?= $form->field($layout, 'primaryColor')->label('Choose a primary color:')->input('color'); ?>
                    <?= $form->field($layout, 'secondaryColor')->label('Choose a secondary color:')->input('color'); ?>
                    <?= $form->field($layout, 'tertiaryColor')->label('Choose a tertiary color:')->input('color'); ?>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading"><div class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
                        Social Integration
                    </a>
                </div></div>
                <div id="collapseFour" class="panel-collapse collapse <?= ($layout->twitterEnable? 'twitterEnable ': '').($layout->youtubeEnable? 'youtubeEnable ': '').($layout->facebookEnable? 'facebookEnable ': '') ?>">
                    <?= $form->field($layout, 'twitterEnable')->label('Enable Twitter Link?')->checkbox([], false); ?>
                    <?= $form->field($layout, 'twitterName', ['autoPlaceholder' => true]); ?>
                    <?= $form->field($layout, 'youtubeEnable')->label('Enable Youtube?')->checkbox([], false); ?>
                    <?= $form->field($layout, 'youtubeUrl', ['autoPlaceholder' => true]); ?>
                    <?= $form->field($layout, 'includeYoutubeFeed')->label('Include Youtube Feed?')->checkbox([], false); ?>
                    <?= $form->field($layout, 'facebookEnable')->label('Enable Facebook?')->checkbox([], false); ?>
                    <?= $form->field($layout, 'facebookUrl', ['autoPlaceholder' => true]); ?>
                </div>
            </div>
        </div>

        <button class="btn btn-primary">Save</button>
        <? ActiveForm::end() ?>

    </div>
<? else: ?>
    <div class="text-center">
        <a href="pullrlayout/add" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> New layout</a>
    </div>
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