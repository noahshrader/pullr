<?
use kartik\widgets\ActiveForm;
use common\models\User;
use common\widgets\user\TwitchLogin;
$user = new User();
$user->setScenario('signup');

?>


<!-- Modal -->
<div class="modal fade" id="signupModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <? $form = ActiveForm::begin([
        'action'=> 'app/site/signup' , 
        'fieldConfig' => ['autoPlaceholder'=>true],
        'enableAjaxValidation' => true,
      ]) ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h5 class="modal-title" id="myModalLabel">Signup</h5>
      </div>
      <div class="modal-body">
          <div><?= TwitchLogin::widget() ?></div>
          <br />

          <?= $form->field($user, 'login');?>
          <?= $form->field($user, 'name') ; ?>
          <?= $form->field($user, 'password')->passwordInput() ?>
          <?= $form->field($user, 'confirmPassword')->passwordInput() ?>
           <br />
           <div class="alert alert-info" >By clicking "Create an account", you are indicating that you have read and agreed to the 
               <a href="app/site/termsofservice" target="_blank">Terms of Service</a> and <a href="app/site/privacypolicy" target="_blank">Private Policy</a>
           </div>
      </div>
      <div class="modal-footer">
          <div class="text-left">
            <button type="submit" class="btn btn-primary">Create an account</button>
            <button class="btn btn-link" onclick="$('#signupModal').modal('hide');$('#loginModal').modal('show');return false;">Already registered?</button>
          </div>
      </div>
      <? ActiveForm::end() ?>
    </div>
  </div>
</div>