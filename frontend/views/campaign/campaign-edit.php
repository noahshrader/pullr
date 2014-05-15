<?

use common\models\Campaign;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;

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
            
            <?= $this->render('campaign-edit-layout', [
                            'form' => $form,
                            'campaign' => $campaign, 
                            'eventsIds' => $eventsIds,
                            'eventsNames' => $eventsNames
                        ]); ?>   
            <div class="panel panel-default">
                <div class="panel-heading"><div class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                            Customizations
                        </a>
                </div></div>
                <div class="panel-collapse collapse" id="collapseThree">
                   
                </div>
            </div>
            <?= $this->render('campaign-edit-social', [
                            'form' => $form,
                            'campaign' => $campaign, 
                            'eventsIds' => $eventsIds,
                            'eventsNames' => $eventsNames
                        ]); ?>   
        </div>

        <button class="btn btn-primary">Save</button>
        <? ActiveForm::end() ?>

    </div>
<? else: ?>
<? endif ?> 
<!-- Modal -->
<div class="modal fade" id="modalCharity" tabindex="-1" role="dialog" aria-labelledby="myModalCharity" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>

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