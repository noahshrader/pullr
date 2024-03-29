<?php
/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var common\models\User $user
 */
$this->title = 'Campaigns';
$user = \Yii::$app->user->identity;
?>

    <section class="list-wrap pane campaign-status-<?=$status?> campaigns-left-menu <?= $editCampaign ? 'sidebar-container': 'panels-wrap open' ?>">
                <?= $this->render('campaigns-sidebar', [ 'status' => $status, 'donorsSelected' => false ]); ?>
                <?= $this->render('campaigns-list', [
                    'campaigns' => $campaigns,
                    'currentCampaign' => $selectedCampaign? $selectedCampaign : $editCampaign
                ]); ?>    
    </section>

    <!-- no campaigns -->
    <? if (!sizeof($campaigns)): ?>
        <section class="campaigns-view-wrap">
            <div id="content" class="blank adv">
                <div class="no-entries">
                    <div>
                        <h3>Do something big.</h3>
                        <h5>Click "<i class="icon mdi-content-add-circle"></i>" above to add your first campaign.</h5>
                    </div>
                </div>
            </div>
        </section>
    <? endif; ?>

    <!-- Main Campaign View -->
    <? if ($selectedCampaign): ?>
         <?= $this->render('campaign-view', [
                    'campaign' => $selectedCampaign
                ]); ?>   
    <? endif ?>
    <? if ($editCampaign):?>


    <!-- Sidebar -->
    <section id="sidepanel" class='sidepanel open'>
        <div class="frontend-right-widget">
            <?= $this->render('campaign-edit', [
                    'campaign' => $editCampaign
                ]); ?>            
        </div>
    </section>
    
    <!-- Charity Browsing -->

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