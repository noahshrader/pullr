<?
use common\models\Campaign;
use yii\helpers\Html;
?>
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
                    <div id="campaign-multichannels">
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