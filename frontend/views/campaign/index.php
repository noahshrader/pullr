<?php
use yii\helpers\Html;
use common\models\Campaign;
/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var common\models\User $user
 */
$this->title = 'Campaigns';
$user = \Yii::$app->user->identity;
?>

    <section class="campaigns-list-wrap campaigns-left-menu <?= $editCampaign ? 'content-container': 'panels-wrap open' ?>">

                <h2><?= Html::encode($this->title) ?> <a href="app/campaign/add" style="float:right; margin-right: 10px; color: #fff" ><i class="glyphicon glyphicon-plus"></i></a></h2>

                    <nav class="campaign-quicknav">
                        <ul>
                            <li><a href="app/campaign" class="<?= $status == Campaign::STATUS_ACTIVE ? 'active': '' ?>">Current</a></li>
                            <li><a href="app/campaign?status=<?= Campaign::STATUS_PENDING ?>" class="<?= $status == Campaign::STATUS_PENDING ? 'active': '' ?>">Archive</a></li>
                            <li><a href="app/campaign" class="">Donors</a></li>
                            <li><a href="app/campaign?status=<?= Campaign::STATUS_DELETED ?>" class="<?= $status == Campaign::STATUS_DELETED ? 'active': '' ?>"><i class="glyphicon glyphicon-trash"></i></a></li>
                        </ul>
                    </nav>

                <div class="campaigns-list ">
                    <? $currentCampaign = $selectedCampaign ? $selectedCampaign : $editCampaign ?>
                    <? foreach ($campaigns as $campaign): ?>
                        <a href="app/campaign/view?id=<?= $campaign->id?>" class="row <?= ($currentCampaign && $campaign->id == $currentCampaign->id)?'active':''; ?> campaign-item">

                            <div class="col-xs-10 main-info">
                                <h3><?= $campaign->name ?></h3>
                                <h4 class="layout-type"><?= $campaign->type ?></h4>
                                <p>$<?= number_format($campaign->amountRaised)?></p>
                                <!-- added the change icons though they are non functional at them moment -->
                            </div>

                            <div class="tick col-xs-2">
                                <strong>&gt;</strong>
                            </div>

                        </a>

                    <? endforeach; ?>
                    <? if (sizeof($campaigns) == 0): ?>
                    <div class='text-center'>
                        No campaigns here yet.
                    </div>
                    <? endif ?>
            </div>
        <div class="row content-container content-container-layout" style="display: none">
            <? foreach ($campaigns as $campaign): ?>
                <div class="col-sm-4 pullr-layout-container">
                    <div class="pullr-layout">
                            <div class="pullr-table">
                                <div class="pullr-table-row">
                                    <div class="change-icons">
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            <? endforeach; ?>
        </div>
    </section>
    <? if ($selectedCampaign): ?>
         <?= $this->render('campaign-view', [
                    'campaign' => $selectedCampaign
                ]); ?>   
    <? endif ?> 
    <? if ($editCampaign):?>
    

    <!-- sidebar -->
    <section id="sidepanel" class='sidepanel open'>
        <div class="frontend-right-widget">
            <?= $this->render('campaign-edit', [
                    'campaign' => $editCampaign
                ]); ?>            
        </div>
    </section>
    
    <!-- Charity Browsing -->

    <section id="sidepaneltwo" class="sidepanel">
        
        <div class="tab-content">

            <div id="modalCharity">

                <div class="charity-list">



                </div>

            </div>
             
        </div>
    </section>

    <!-- Theme Browsing -->

    <section id="sidepanelthree" class="sidepanel">
        
        <div class="tab-content">

            <div id="modalThemes">

                <div class="themes-list">

                    

                </div>

            </div>
             
        </div>
    </section>


    <? endif; ?>