<?
use kartik\widgets\ActiveForm;
use common\models\LoginForm;

$model = new LoginForm();
$model->load($_POST);
?>
<button class='btn btn-link'data-toggle="modal" data-target="#loginModal" >Login</button>

<!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <? $form = ActiveForm::begin([
        'action'=> 'site/login' , 
        'fieldConfig' => ['autoPlaceholder'=>true],
        'enableAjaxValidation' => true,
      ]) ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h5 class="modal-title" id="myModalLabel">Login</h5>
      </div>
      <div class="modal-body">
          <?= $form->field($model, 'login')->textInput(['placeHolder' => 'Email address (username)']);?>
          <?= $form->field($model, 'password')->passwordInput() ?>
          <?= $form->field($model, 'rememberMe')->checkbox() ?>
          
          <div><a href="site/requestpasswordreset">Forgot password?</a></div>
           <div class="alert">Do you already have an account on one of these sites? Click the logo to log in with it here:</div>
          <?php echo \nodge\eauth\Widget::widget(array('action' => 'site/login')); ?>
           <br />
      </div>
      <div class="modal-footer">
          <div class="text-left">
            <button type="submit" class="btn btn-primary">Login</button>
            <button class="btn btn-link" onclick="$('#loginModal').modal('hide');$('#signupModal').modal('show');return false;">Signup</button>
          </div>
      </div>
      <? ActiveForm::end() ?>
    </div>
  </div>
</div>