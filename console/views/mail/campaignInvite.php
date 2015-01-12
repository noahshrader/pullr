<?
use common\components\Application;

$baseUrl = Application::getBaseUrl();
?>

<div class="text-center">
	<p>You were invited to a fundraiser!</p>
	<div class="callout">
		<h2 class="green"><?= htmlspecialchars($campaign->name) ?></h2>
		<h5><?= htmlspecialchars($campaign->user->name)?></h5>
	</div>
    <p><a href="<?=$baseUrl?>/app">View Your Invite</a></p>
    <p>Click on the link above to accept or decline this invitation via your dashboard. Accepting this invitation will allow you to connect your own campaigns to <?= htmlspecialchars($campaign->name) ?>.</p>
</div>