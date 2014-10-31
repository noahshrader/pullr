<?php
use yii\helpers\Html;
use common\models\Campaign;
/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var common\models\User $user
 */
$this->title = 'Donors';
$user = \Yii::$app->user->identity;
?>

<section class="list-wrap donors pane">
    <?//= $this->render('campaigns-sidebar', [ 'status' => null, 'donorsSelected' => true ]); ?>
    <?= $this->render('donors-list', [
        'donors' => $donors,
    ]); ?>    
</section>

<? if (!sizeof($donors)):?>
    <section class="donor-view-wrap">
        <div id="content" class="blank adv">
            <div class="no-entries">
                <div>
                    <h3>Donors will be arriving soon.</h3>
                </div>
            </div>
        </div>
    </section>
<? endif;?>

<? if ($viewDonorParams): ?>
    <?= $this->render('donor-view', $viewDonorParams
    ); ?>   
<? endif ?>