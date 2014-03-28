<?
use common\components\Application;

$baseUrl = Application::getBaseUrl();
?>
<div>
    <div>Thank you for registration on <a href="<?= $baseUrl ?>">pullr.io</a>.</div>
    <div>
        To confirm email please follow that 
        <a href="<?= $baseUrl.'/site/confirmemail?email='. urlencode($email).'&key='.urlencode($key)?>"><strong>link</strong></a>.
    </div>
</div>