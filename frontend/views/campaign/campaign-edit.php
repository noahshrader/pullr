<?

use common\models\Campaign;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->registerJsFile('@web/js/campaign/edit.js', common\assets\CommonAsset::className());
$user = \Yii::$app->user->identity;
?>

    <? if ($campaign): ?>

    <div id="campaignEdit" class="layout-edit" data-campaignType="<?= htmlspecialchars($campaign->type) ?>" data-id="<?= $campaign->id ?>">
        <h4> <?= ($campaign->name)?$campaign->name:'New campaign' ?></h4>
        <? $form = ActiveForm::begin(['options' => [
                            'enctype' => 'multipart/form-data', 'method' => 'POST']]) ?>
        <ul class="nav nav-tabs">
            <li class="active"><a href="<?= Url::to()?>#general" data-toggle="tab">Home</a></li>
            <li><a href="<?= Url::to()?>#layout" data-toggle="tab">Layout</a></li>
            <li><a href="<?= Url::to()?>#social" data-toggle="tab">Social</a></li>
        </ul>
        <div class="tab-content" id="accordion">
            <div class="tab-pane fade in active" id="general">
             <?= $this->render('campaign-edit-general', [
                            'form' => $form,
                            'campaign' => $campaign
                        ]); ?>    
            </div>
            <div class="tab-pane" id="layout">
            <?= $this->render('campaign-edit-layout', [
                            'form' => $form,
                            'campaign' => $campaign, 
                        ]); ?>   
            </div>
            <div class="tab-pane" id="social">
            <?= $this->render('campaign-edit-social', [
                            'form' => $form,
                            'campaign' => $campaign, 
                        ]); ?>   
            </div>
        </div>
        <div class="text-center">
            <button class="btn btn-primary">Update</button>
        </div>
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