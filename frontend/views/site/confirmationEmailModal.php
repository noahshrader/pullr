<?
use common\models\User;

$user = new User();
$user->setScenario('signup');

?>

<!-- Modal -->
<div class="modal fade" id="confirmationEmailModal" tabindex="-1" role="dialog" data-show="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h5 class="modal-title" id="myModalLabel">Email confirmation</h5>
      </div>
      <div class="modal-body">
          <div class="text-center">
               <h2><i class='glyphicon glyphicon-ok'></i></h2>
               <br />
               <br />
               <div>Thanks for registering. Please check your inbox for a confirmation email.</div>
               <br />
               <br />
               <button type="submit" class="btn btn-primary" onclick="$.post('site/resendemailconfirmation'); alert('Has been sent');">Resend Email</button>
          </div>
      </div>
    </div>
  </div>
</div>