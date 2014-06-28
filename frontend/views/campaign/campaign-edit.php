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

                <? if ($campaign->status != Campaign::STATUS_DELETED): ?>
                <li>
                    <a href="app/campaign" onclick="return campaignChangeStatus(<?= $campaign->id ?>, '<?= Campaign::STATUS_DELETED ?>')">
                        <i class="icon icon-remove"></i>
                        <!-- Remove -->
                    </a>
                </li>
                <? endif ?>

                <? if ($campaign->status != Campaign::STATUS_PENDING): ?>
                <li>
                    <a href="app/campaign" onclick="return campaignChangeStatus(<?= $campaign->id ?>,  '<?= Campaign::STATUS_PENDING ?>')">
                        <i class="icon icon-archive"></i>
                        <!-- Archive -->
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

            </ul>

        </div>

        <div class="col-md-6 campaign-nav">

            <ul class="campaign-buttons">

                <li>
                    <a href='<?= $user->getUrl() . $campaign->alias ?>' target="_blank">
                        <i class="icon icon-view"></i>
                        <!-- View -->
                        View Campaign
                    </a>
                </li>

                <li>
                    <a href="https://github.com/noahshrader/pullr/blob/master/docs/SHORTCODES.md">
                        <i class="icon icon-code"></i>
                        <!-- Shortcodes -->
                        XML
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

    <h1> <?= ($campaign->name)?$campaign->name:'New campaign' ?></h1>
        
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="<?= Url::to()?>#general" data-toggle="tab" class="icon-cog"><span>General</span></a>
            </li>
            <li>
                <a href="<?= Url::to()?>#campaign-edit-form-container" data-toggle="tab" class="icon-cog"><span>Form</span></a>
            </li>
            <? if ($user->getPlan()==Plan::PLAN_PRO): ?>
                <li id="campaign-edit-team-li">
                    <a href="<?= Url::to()?>#team" data-toggle="tab" class="icon-layout"><span>Team</span></a>
                </li>
            <? endif ?>
            <li>
                <a href="<?= Url::to()?>#layout" data-toggle="tab" class="icon-layout"><span>Layout</span></a>
            </li>
            <li>
                <a href="<?= Url::to()?>#social" data-toggle="tab" class="icon-comment"><span>Social</span></a>
            </li>
        </ul>
       
       <div class="tab-content" id="accordion">
            <div class="tab-pane fade in active" id="general">
             <?= $this->render('campaign-edit-general', [
                            'form' => $form,
                            'campaign' => $campaign
                        ]); ?>    
            </div>
           <div class="tab-pane" id="campaign-edit-form-container">
             <?= $this->render('campaign-edit-form', [
                            'form' => $form,
                            'campaign' => $campaign
                        ]); ?>    
           </div>
            <? if ($user->getPlan()==Plan::PLAN_PRO): ?>
            <div class="tab-pane" id="team">
                 <?= $this->render('campaign-edit-team', [
                            'form' => $form,
                            'campaign' => $campaign, 
                        ]); ?>   
            </div>
            <? endif; ?>
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

</div>

<div id='modal-social-link' class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            
        </div>
    </div>
</div>