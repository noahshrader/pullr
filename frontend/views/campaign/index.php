<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Notification;
use kartik\widgets\FileInput;

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
    <div class='pullr-table'>
        <div class="row pullr-table-row">
            <div class="<?= $selectedCampaign ? 'col-xs-10': 'col-xs-12' ?>">
                <h1><?= Html::encode($this->title) ?> <a href="app/campaign/add" style="float:right" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> New campaign</a></h1>
                <div class="row content-container content-container-layout">
                    <? foreach ($campaigns as $campaign): ?>
                        <div class="col-sm-4 pullr-layout-container">
                            <div class="pullr-layout">
                                    <div class="pullr-table">
                                        <div class="pullr-table-row">
                                            <div class="change-icons">
                                                <div>
                                                    <a href='<?= $user->getUrl().$campaign->alias ?>'><i class="glyphicon glyphicon-search"></i></a>
                                                </div>
                                                <div>
                                                    <a href="app/campaign/edit?id=<?= $campaign->id?>"><i class="glyphicon glyphicon-edit"></i></a>
                                                </div>
                                                <div>
                                                    
                                                    <a href="app/campaign" onclick="return layoutRemove(<?=$campaign->id?>)" ><i class="glyphicon glyphicon-remove"></i></a>
                                                </div>
                                            </div>
                                            <div class="main-info" >
                                                <div><?= $campaign->name ?></div>
                                                <div class="layout-type"><?= $campaign->type ?></div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    <? endforeach; ?>
                </div>
            </div>
            <? if ($selectedCampaign):?>
            <!-- table-cell div -->
            <div class='col-xs-2 table-cell'>
                <div class="frontend-right-widget">
                    <?= $this->render('campaign-edit', [
                            'campaign' => $selectedCampaign
                        ]); ?>            
                </div>
            </div>
            <? endif; ?>
        </div>
    </div>