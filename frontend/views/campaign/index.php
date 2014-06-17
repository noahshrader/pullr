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
<script type="text/javascript">
    function layoutRemove(id){
        if (confirm('Are you sure to remove?')){
            $.post('app/campaign/remove', {id: id});
            return true;
        }
        return false;
    }
</script>
<div style="height: 100%">
            <section class="campaigns-left-menu <?= $editCampaign ? 'content-container': 'panels-wrap open' ?>">
                <h2><?= Html::encode($this->title) ?> <a href="app/campaign/add" style="float:right; margin-right: 10px; color: #fff" ><i class="glyphicon glyphicon-plus"></i></a></h2>
                <div class="text-center">
                    <div class="btn-group">
                        <a type="button" href="app/campaign" class="btn btn-default active">Active</a>
                        <a type="button" class="btn btn-default">Past</a>
                        <a type="button" class="btn btn-default">Donors</a>
                    </div>
                </div>
                <div class="campaigns-list ">
                    <? foreach ($campaigns as $campaign): ?>
                        <a href="app/campaign/view?id=<?= $campaign->id?>" class="row <?= ($selectedCampaign && $campaign->id == $selectedCampaign->id)?'active':''; ?>">
                            <div class="col-xs-10 main-info">
                                <div><?= $campaign->name ?></div>
                                <div class="layout-type"><?= $campaign->type ?></div>
                                <div>$<?= number_format($campaign->amountRaised)?></div>
                            </div>
                            <div class="col-xs-2" style="vertical-align: middle">
                                <strong>&gt;</strong>
                            </div>
                        </a>
                    <? endforeach; ?>
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
</div>