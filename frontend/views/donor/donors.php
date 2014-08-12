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

<section class="campaigns-list-wrap pane">
    <?//= $this->render('campaigns-sidebar', [ 'status' => null, 'donorsSelected' => true ]); ?>
    <?= $this->render('donors-list', [
        'donors' => $donors,
    ]); ?>    
</section>
<? if ($viewDonorParams): ?>
    <?= $this->render('donor-view', $viewDonorParams
    ); ?>   
<? endif ?>