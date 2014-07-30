<?

use common\models\Campaign;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;
use common\models\Plan;

$this->registerJsFile('@web/js/campaign/edit.js', common\assets\CommonAsset::className());
/**
 * @var User;
 */
$user = \Yii::$app->user->identity;
?>

    <? if ($campaign): ?>
    <div id="campaignEdit" class="layout-edit" data-campaignType="<?= htmlspecialchars($campaign->type) ?>" data-id="<?= $campaign->id ?>">
    <? if (!$campaign->isNewRecord): ?>

    <div class="campaign-actions">
        <div class="col-md-6 campaign-nav">
            <ul class="campaign-quick-links">
                <li>
                    <a href="app/campaign/view?id=<?= $campaign->id ?>">
                        <i class="icon icon-piechart"></i>
                        <!-- Overview -->
                    </a>
                </li>
                <li class="active">
                    <a href="app/campaign/edit?id=<?= $campaign->id ?>">
                        <i class="icon icon-edit"></i>
                        <!-- Edit -->
                    </a>
                </li>
                <? if ($campaign->status != Campaign::STATUS_PENDING): ?>
                <li>
                    <a href="app/campaign" onclick="return campaignChangeStatus(<?= $campaign->id ?>,  '<?= Campaign::STATUS_PENDING ?>')">
                        <i class="icon icon-archive"></i>
                        <!-- Archive -->
                    </a>
                </li>
                <? endif ?>
                <? if ($campaign->status != Campaign::STATUS_DELETED): ?>
                <li>
                    <a href="app/campaign" onclick="return campaignChangeStatus(<?= $campaign->id ?>, '<?= Campaign::STATUS_DELETED ?>')">
                        <i class="icon icon-remove"></i>
                        <!-- Remove -->
                    </a>
                </li>
                <? endif ?>
                <? if ($campaign->status != Campaign::STATUS_ACTIVE): ?>
                <li>
                    <a href="app/campaign" onclick="return campaignChangeStatus(<?= $campaign->id ?>,  '<?= Campaign::STATUS_ACTIVE ?>')">
                        <i class="icon icon-recover"></i>
                        <!-- Restore -->
                    </a>
                </li>
                <? endif ?>
                <li>
                    <? /* $campaign->user and $user can be different because of concept of parent campaigns*/ ?>
                    <a href='<?= $campaign->user->getUrl() . $campaign->alias ?>/json' target="_blank">
                        <i class="icon icon-code"></i>
                    </a>
                </li>
            </ul>
        </div>
        <div class="col-md-6 campaign-nav">
            <ul class="campaign-buttons">
                <li>
                    <? /* $campaign->user and $user can be different because of concept of parent campaigns*/ ?>
                    <a href='<?= $campaign->user->getUrl() . $campaign->alias ?>' target="_blank">
                        <!-- View -->
                        View Campaign
                    </a>
                </li>
            </ul>
        </div>
        <div class="clearfix"></div>
    </div>
    <? endif ?>
    <? $form = ActiveForm::begin(['options' => [
        'enctype' => 'multipart/form-data', 'method' => 'POST']]) ?>
    <div class="campaign-edit-wrap">
        <h1><?= ($campaign->name)?$campaign->name:'New campaign' ?></h1>
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="<?= Url::to()?>#general" data-toggle="tab" class="icon icon-settings"><span>General</span></a>
            </li>
            <li>
                <a href="<?= Url::to()?>#campaign-edit-form-container" data-toggle="tab" class="icon icon-template"><span>Form</span></a>
            </li>
            <? if ($user->getPlan()==Plan::PLAN_PRO): ?>
                <li id="campaign-edit-team-li">
                    <a href="<?= Url::to()?>#team" data-toggle="tab" class="icon icon-usergroup"><span>Team</span></a>
                </li>
            <? endif ?>
            <li>
                <a href="<?= Url::to()?>#layout" data-toggle="tab" class="icon icon-brush"><span>Layout</span></a>
            </li>
            <li>
                <a href="<?= Url::to()?>#social" data-toggle="tab" class="icon icon-share"><span>Social</span></a>
            </li>
        </ul>
        <div class="tab-content" id="accordion">
            <div class="tab-pane fade in active" id="general"> <!-- general settings -->
             <?= $this->render('campaign-edit-general', [
                            'form' => $form,
                            'campaign' => $campaign
                        ]); ?>    
            </div>
            <div class="tab-pane" id="campaign-edit-form-container"> <!-- donation form settings -->
             <?= $this->render('campaign-edit-form', [
                            'form' => $form,
                            'campaign' => $campaign
                        ]); ?>    
            </div>
            <? if ($user->getPlan()==Plan::PLAN_PRO): ?>
            <div class="tab-pane" id="team"> <!-- team settings -->
                 <?= $this->render('campaign-edit-team', [
                            'form' => $form,
                            'campaign' => $campaign, 
                        ]); ?>   
            </div>
            <? endif; ?>
            <div class="tab-pane" id="layout"> <!-- layout settings -->
            <?= $this->render('campaign-edit-layout', [
                            'form' => $form,
                            'campaign' => $campaign, 
                        ]); ?>   
            </div>
            <div class="tab-pane" id="social"> <!-- social settings -->
            <?= $this->render('campaign-edit-social', [
                            'form' => $form,
                            'campaign' => $campaign, 
                        ]); ?>   
            </div>
            <div class="text-center">
                <button class="btn btn-primary">Update</button>
            </div>
        </div>
        <? ActiveForm::end() ?>

    </div>
<? else: ?>
<? endif ?> 
</div>
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