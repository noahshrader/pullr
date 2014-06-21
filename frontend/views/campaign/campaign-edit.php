<?

use common\models\Campaign;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\User;
use common\models\Plan;

$this->registerJsFile('@web/js/campaign/edit.js', common\assets\CommonAsset::className());
/**
 * @var User;
 */
$user = \Yii::$app->user->identity;
?>

    <? if ($campaign): ?>

    <div id="campaignEdit" class="layout-edit" data-campaignType="<?= htmlspecialchars($campaign->type) ?>" data-id="<?= $campaign->id ?>">

            <h1 class="section-header"> <?= ($campaign->name)?$campaign->name:'New campaign' ?></h1>



            <ul class="campaign-actions">

                <li>
                    <a href='<?= $user->getUrl() . $campaign->alias ?>'>
                        <i class="glyphicon glyphicon-search"></i>
                        <br>
                        View campaign
                    </a>
                </li>
                <li>
                    <a href="app/campaign/edit?id=<?= $campaign->id ?>">
                        <i class="glyphicon glyphicon-edit"></i>
                        <br>
                        Edit campaign
                    </a>
                </li>
                <? if ($campaign->status != Campaign::STATUS_PENDING): ?>
                    <li>
                        <a href="app/campaign" onclick="return campaignChangeStatus(<?= $campaign->id ?>,  '<?= Campaign::STATUS_PENDING ?>')">
                            <i class="glyphicon glyphicon-book"></i>
                            <br>
                            Campaign archive
                        </a>
                    </li>
                <? endif ?>
                 <? if ($campaign->status != Campaign::STATUS_ACTIVE): ?>
                    <li>
                        <a href="app/campaign" onclick="return campaignChangeStatus(<?= $campaign->id ?>,  '<?= Campaign::STATUS_ACTIVE ?>')">
                            <i class="glyphicon glyphicon-ok"></i>
                            <br>
                            Campaign restore
                        </a>
                    </li>
                <? endif ?>
                    <li>
                        <a href="https://github.com/noahshrader/pullr/blob/master/docs/SHORTCODES.md">
                            <br>
                            Shortcodes
                        </a>
                    </li>
                <? if ($campaign->status != Campaign::STATUS_DELETED): ?>
                    <li>
                        <a href="app/campaign" onclick="return campaignChangeStatus(<?= $campaign->id ?>, '<?= Campaign::STATUS_DELETED ?>')">
                            <i class="glyphicon glyphicon-remove"></i>
                            <br>
                            Campaign remove
                        </a>
                    </li>
                <? endif ?>
                </ul>


        <? $form = ActiveForm::begin(['options' => [
                            'enctype' => 'multipart/form-data', 'method' => 'POST']]) ?>

    <div class="campaign-edit-wrap">
        
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