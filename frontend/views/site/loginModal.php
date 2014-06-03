<?
use kartik\widgets\ActiveForm;
use common\models\LoginForm;
use common\widgets\user\TwitchLogin;
$model = new LoginForm();
$model->load($_POST);
?>


<!-- Removing Login Modal so that it appears on the home page instead -->

<div class="container">
  <div class="row">
    <div class="col-sm-8">
      <? $form = ActiveForm::begin([
        'action'=> 'app/site/login' , 
        'fieldConfig' => ['autoPlaceholder'=>true],
        'enableAjaxValidation' => true,
      ]) ?>

          <div><?= TwitchLogin::widget() ?></div>
           <br />
          <?= $form->field($model, 'login')->textInput(['placeHolder' => 'Email address (username)']);?>
          <?= $form->field($model, 'password')->passwordInput() ?>
          <?= $form->field($model, 'rememberMe')->checkbox() ?>
          
          <div><a href="app/site/requestpasswordreset">Forgot password?</a></div>

            <button type="submit" class="btn btn-primary">Login</button>
            <button class="btn btn-link" onclick="$('#loginModal').modal('hide');$('#signupModal').modal('show');return false;">Signup</button>

      </div>
      <? ActiveForm::end() ?>
    </div>
  </div>
</div>