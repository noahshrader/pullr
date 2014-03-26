<? 
use common\models\Layout;
use kartik\widgets\ActiveForm;

$this->registerJsFile('@web/js/pullrlayout/edit.js', common\assets\CommonAsset::className());
?>
<? if ($layout): ?>
<div class="layout-edit" data-id="<?= $layout->id?>">
    <h4> <?= $layout->name ?></h4>
    <? $form = ActiveForm::begin() ?>
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
                    <?= $form->field($layout, 'name',['autoPlaceholder' => true]) ?>
                    <?= $form->field($layout, 'domain',['autoPlaceholder' => true]) ?>
                    <?= $form->field($layout, 'streamService',['autoPlaceholder' => true])->dropDownList(array_combine(Layout::$STREAM_SERVICES, Layout::$STREAM_SERVICES)) ?>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading"><div class="panel-title">
                         <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                            Layout options
                        </a>
                </div></div>
                <div id="collapseTwo" class="panel-collapse collapse<?= $layout->chat?' chatOn':''?>" data-layout-type="<?= str_replace(' ','',$layout->type) ?>">
                    <?= $form->field($layout, 'type',['autoPlaceholder' => true])->dropDownList(array_combine(Layout::$TYPES, Layout::$TYPES)) ?>
                    <?= $form->field($layout, 'channelName',['autoPlaceholder' => true]); ?>
                    <?= $form->field($layout, 'channelTeam',['autoPlaceholder' => true]);?>
                    <div id="layout-multichannels">
                        <? if ($layout->isNewRecord): ?>
                        <div class="label label-danger">Save layout before adding channels</div>
                        <? endif ?>
                        <input type="text" id="addLayoutTeam" placeholder="Add channel"> <a class="btn btn-success btn-xs" onclick="addNewLayoutTeam()"> <i class="glyphicon glyphicon-plus"></i></a>
                        <div id="layoutTeams">
                            
                        </div>
                    </div>
                    <input type="checkbox" id="switch-change" checked>
                    <?= $form->field($layout, 'chat')->label('Chat on?')->checkbox([], false); ?>
                    <?= $form->field($layout, 'chatToggle')->label('Use toggle?')->checkbox([], false); ?>
                    <?= $form->field($layout, 'enableDonations')->label('Enable donations?')->checkbox([], false); ?>
                </div>
            </div>
            
            <div class="panel panel-default">
                <div class="panel-heading"><div class="panel-title">
                         <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                            Customizations
                        </a>
                </div></div>
                <div class="panel-collapse collapse" id="collapseThree">
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