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
    <div id="campaignEdit" class="layout-edit <? if($campaign->teamEnable):?>team-enabled<? endif ?>" data-campaignType="<?= htmlspecialchars($campaign->type) ?>" data-id="<?= $campaign->id ?>">
    <? if (!$campaign->isNewRecord): ?>

    <div class="campaign-actions">
        <div class="col-md-6 campaign-nav">
            <? if (!$campaign->isParentForCurrentUser()): ?>
            <ul class="campaign-quick-links">
                <li>
                    <a class="actions-toggle icon-mobile"></a>
                    <ul>
                        <li>
                            <a href="app/campaign/view?id=<?= $campaign->id ?>">
                                <i class="icon icon-piechart"></i>
                                <!-- Overview -->
                                Overview
                            </a>
                        </li>
                        <li class="active">
                            <a href="app/campaign/edit?id=<?= $campaign->id ?>">
                                <i class="icon icon-edit"></i>
                                <!-- Edit -->
                                Edit
                            </a>
                        </li>
                        <li>
                            <? /* $campaign->user and $user can be different because of concept of parent campaigns*/ ?>
                            <a href='<?= $campaign->user->getUrl() . $campaign->alias ?>/json' target="_blank">
                                <i class="icon icon-code"></i>
                                JSON
                            </a>
                        </li>
                        <? if ($campaign->status != Campaign::STATUS_PENDING): ?>
                        <li>
                            <a href="app/campaign" onclick="return campaignChangeStatus(<?= $campaign->id ?>,  '<?= Campaign::STATUS_PENDING ?>')">
                                <i class="icon icon-archive"></i>
                                <!-- Archive -->
                                Archive
                            </a>
                        </li>
                        <? endif ?>
                        <? if ($campaign->status != Campaign::STATUS_DELETED): ?>
                        <li>
                            <a href="app/campaign" onclick="return campaignChangeStatus(<?= $campaign->id ?>, '<?= Campaign::STATUS_DELETED ?>')">
                                <i class="icon icon-trash"></i>
                                <!-- Remove -->
                                Delete
                            </a>
                        </li>
                        <? endif ?>
                        <? if ($campaign->status != Campaign::STATUS_ACTIVE): ?>
                        <li>
                            <a href="app/campaign" onclick="return campaignChangeStatus(<?= $campaign->id ?>,  '<?= Campaign::STATUS_ACTIVE ?>')">
                                <i class="icon icon-recover"></i>
                                <!-- Restore -->
                                Restore
                            </a>
                        </li>
                    </ul>
                </li>
                <? endif ?>
            </ul>
            <? endif ?>
        </div>
        <div class="col-md-6 campaign-nav">
            <? /* $campaign->user and $user can be different because of concept of parent campaigns*/ ?>
            <a class="view-campaign" href='<?= $campaign->user->getUrl() . $campaign->alias ?>' target="_blank">View Campaign</a>
        </div>
    </div>
    <? endif ?>
    <? $form = ActiveForm::begin(['options' => [
        'enctype' => 'multipart/form-data', 'method' => 'POST']]) ?>
    <section id="content" class="campaign-edit-wrap pane adv">
        <ul class="content-nav cf">
            <li class="active">
                <a href="<?= Url::to()?>#general" data-toggle="tab">General</a>
            </li>
            <li>
                <a href="<?= Url::to()?>#layout" data-toggle="tab">Layout</a>
            </li>
            <li>
                <a href="<?= Url::to()?>#campaign-edit-form-container" data-toggle="tab">Form</a>
            </li>
            <? if ($user->getPlan()==Plan::PLAN_PRO): ?>
                <li id="campaign-edit-team-li">
                    <a href="<?= Url::to()?>#team" data-toggle="tab">Team</a>
                </li>
            <? endif ?>
            <li>
                <a href="<?= Url::to()?>#social" data-toggle="tab">Social</a>
            </li>
        </ul>
        <div class="module">
            <div class="spinner-wrap">
                <div class="spinner">
                    <div class="cube1"></div>
                    <div class="cube2"></div>
                </div>
            </div>
            <div class="tab-content" id="accordion">
                <div class="tab-pane fade in active" id="general"> <!-- general settings -->
                 <?= $this->render('campaign-edit-general', [
                                'form' => $form,
                                'campaign' => $campaign
                            ]); ?>    
                </div>
                <div class="tab-pane" id="layout"> <!-- layout settings -->
                <?= $this->render('campaign-edit-layout', [
                                'form' => $form,
                                'campaign' => $campaign, 
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
    </section>
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