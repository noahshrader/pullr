<?

use common\models\Campaign;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;
use common\models\Plan;

$this->registerJsFile('@web/js/campaign/edit.js', [
    'depends' => common\assets\CommonAsset::className(),
]);
/**
 * @var User;
 */
$user = \Yii::$app->user->identity;
?>

    <? if ($campaign): ?>
    <div id="campaignEdit" class="layout-edit <? if($campaign->teamEnable):?>team-enabled<? endif ?>" data-campaignType="<?= htmlspecialchars($campaign->type) ?>" data-id="<?= $campaign->id ?>">
    <? $form = ActiveForm::begin(['options' => [
        'enctype' => 'multipart/form-data', 'method' => 'POST']]) ?>
    <section id="content" class="campaign-edit-wrap pane adv">
        <div class="content-wrap">
            <? if (!$campaign->isNewRecord): ?>
            <div class="view-actions">
                <div class="campaign-nav">
                    <? if (!$campaign->isParentForCurrentUser()): ?>
                    <ul class="campaign-quick-links dropdown">
                        <li>
                            <a class="actions-toggle mdi-navigation-menu"></a>
                            <ul>
                                <li class="cf">
                                    <a href="app/campaigns/view?id=<?= $campaign->id ?>">
                                        <!-- Overview -->
                                        Overview
                                    </a>
                                </li>
                                <li class="active cf">
                                    <a <?if(in_array($campaign->status, [Campaign::STATUS_DELETED, Campaign::STATUS_PENDING])):?>class="disabled"<?else:?>href="app/campaigns/edit?id=<?= $campaign->id ?>"<?endif;?>>
                                        <!-- Edit -->
                                        Edit
                                    </a>
                                </li>
                                <li class="cf">
                                    <a href='<?= $campaign->user->getUrl() . $campaign->alias ?>/donate' target="_blank">
                                        Donation Form
                                    </a>
                                </li>
                                <li class="cf">
                                    <a href='<?= $campaign->user->getUrl() . $campaign->alias ?>/json' target="_blank">
                                        JSON
                                    </a>
                                </li>
                                <li class="cf">
                                    <a class="disabled">
                                        Widgets
                                    </a>
                                </li>
                                <? if ($campaign->status != Campaign::STATUS_PENDING): ?>
                                <li class="cf">
                                    <a href="app/campaigns" onclick="return campaignChangeStatus(<?= $campaign->id ?>,  '<?= Campaign::STATUS_PENDING ?>')">
                                        <!-- Archive -->
                                        Archive
                                    </a>
                                </li>
                                <? endif ?>
                                <? if ($campaign->status != Campaign::STATUS_ACTIVE): ?>
                                <li class="cf">
                                    <a href="app/campaigns" onclick="return campaignChangeStatus(<?= $campaign->id ?>,  '<?= Campaign::STATUS_ACTIVE ?>')">
                                        <!-- Restore -->
                                        Restore
                                    </a>
                                </li>
                                <? endif ?>
                                <? if ($campaign->status != Campaign::STATUS_DELETED): ?>
                                <li class="cf">
                                    <a href="app/campaigns" onclick="return campaignChangeStatus(<?= $campaign->id ?>, '<?= Campaign::STATUS_DELETED ?>')">
                                        <!-- Remove -->
                                        Delete
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <? endif ?>
                    </ul>
                    <? endif ?>
                </div>
                <h4>
                    <a href="app/campaigns/view?id=<?= $campaign->id ?>"><?= ($campaign->name)?$campaign->name:'New Campaign' ?></a>
                </h4>
                <? /* $campaign->user and $user can be different because of concept of parent campaigns*/ ?>
                <a class="view-campaign" href='<?= $campaign->user->getUrl() . urlencode($campaign->alias); ?>' target="_blank"><i class="icon mdi-action-visibility"></i></a>
            </div>
            <? endif ?>
            <ul class="content-nav cf">
                <li class="active">
                    <a href="<?= Url::to()?>#general" data-toggle="tab">General</a>
                </li>
                <li>
                    <a href="<?= Url::to()?>#layout" data-toggle="tab">Campaign Page</a>
                </li>
                <li>
                    <a href="<?= Url::to()?>#campaign-edit-form-container" data-toggle="tab">Donation Form</a>
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
            <div class="tab-content">
                <div class="spinner-wrap">
                    <div class="spinner">
                        <div class="rect1"></div>
                        <div class="rect2"></div>
                        <div class="rect3"></div>
                        <div class="rect4"></div>
                        <div class="rect5"></div>
                    </div>
                </div>
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
                <div class="btn-container">
                    <a href="app/campaigns/view?id=<?= $campaign->id ?>" class="btn btn-secondary">Cancel</a>
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
<div id="modalCharity" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalCharity" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>
<div id="modalThemes" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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