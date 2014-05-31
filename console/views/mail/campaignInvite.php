<?
use common\components\Application;

$baseUrl = Application::getBaseUrl();
?>

<div>
    <h2>You was invited to fundraiser "<?= htmlspecialchars($campaign->name) ?>" by <?= htmlspecialchars($campaign->user->name)?> </h2>
    <br>
    <div>
        <a href="<?=$baseUrl?>/app/campaigninvite">View your invites</a>
    </div>
</div>