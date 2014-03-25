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
$this->title = 'Layouts';
?>
<script type="text/javascript">
    function layoutRemove(id){
        if (confirm('Are you sure to remove?')){
            $.post('pullrlayout/remove', {id: id});
            return true;
        }
        return false;
    }
</script>
    <div class='pullr-table'>
        <div class="row pullr-table-row">
            <div class="col-xs-10">
                <h1><?= Html::encode($this->title) ?></h1>
                <div class="row content-container content-container-layout">
                    <? foreach ($layouts as $layout): ?>
                        <div class="col-sm-4 pullr-layout-container">
                            <div class="pullr-layout">
                                    <div class="pullr-table">
                                        <div class="pullr-table-row">
                                            <div class="change-icons">
                                                <div>
                                                    <a href="pullrlayout/edit?id=<?= $layout->id?>"><i class="glyphicon glyphicon-edit"></i></a>
                                                </div>
                                                <div>
                                                    
                                                    <a href="pullrlayout" onclick="return layoutRemove(<?=$layout->id?>)" ><i class="glyphicon glyphicon-remove"></i></a>
                                                </div>
                                            </div>
                                            <div class="main-info" >
                                                <div><?= $layout->name ?></div>
                                                <div class="layout-type"><?= $layout->type ?></div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    <? endforeach; ?>
                </div>
            </div>
            <!-- table-cell div -->
            <div class='col-xs-2 table-cell'>
                <div class="frontend-right-widget">
                    <?= $this->render('layout-edit', [
                            'layout' => $selectedLayout
                        ]); ?>            
                </div>
            </div>
        </div>
    </div>
