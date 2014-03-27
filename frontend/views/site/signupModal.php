<?
use kartik\widgets\ActiveForm;
use common\models\User;

$user = new User();
$user->setScenario('signup');

?>
<button class='btn btn-link'data-toggle="modal" data-target="#signupModal" >Singup</button>

<!-- Modal -->
<div class="modal fade" id="signupModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <? $form = ActiveForm::begin([
        'action'=> 'site/signup' , 
        'fieldConfig' => ['autoPlaceholder'=>true],
        'enableAjaxValidation' => true,
      ]) ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h5 class="modal-title" id="myModalLabel">Signup</h5>
      </div>
      <div class="modal-body">
          <?= $form->field($user, 'login')->textInput(['placeHolder' => 'Email address (username)']);?>
          <?= $form->field($user, 'password')->passwordInput() ?>
          <?= $form->field($user, 'confirmPassword')->passwordInput() ?>
          
           <div class="alert">Do you already have an account on one of these sites? Click the logo to log in with it here:</div>
          <?php echo \nodge\eauth\Widget::widget(array('action' => 'site/login')); ?>
           <br />
           <div class="alert alert-info" >By clicking "Create an account", you are indicating that you have read and agreed to the 
               <a href="site/termsofservice" target="_blank">Terms of Service</a> and <a href="site/privacypolicy" target="_blank">Private Policy</a>
           </div>
      </div>
      <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Create an account</button>
      </div>
      <? ActiveForm::end() ?>
    </div>
  </div>
</div>