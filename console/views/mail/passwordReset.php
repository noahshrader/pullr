<?
use common\components\Application;

$baseUrl = Application::getBaseUrl();
?>
<div>
    <div>You recently initiated a password reset for <a href="<?= $baseUrl ?>">pullr.io</a>.</div>
    <div>
        To complete the process, click the link below 
        <a href="<?= $baseUrl.'/app/site/resetpassword?token='. $passwordResetToken?>"><strong>Reset now ></strong></a>.
    </div>
</div>