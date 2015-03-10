<? use common\components\PullrUtils; ?>
<?
	$userNameArray = [];
	if ( ! $groupDonors ) {
		foreach ($donors as $donor) {
			$userNameArray[] = ' ' . $donor['name'] . ' ($' . PullrUtils::formatNumber($donor['amount']) . ')';
		}
	} else {
		foreach ($groupDonors as $amount => $donors) {
			$donorArray = [];
			foreach ($donors as $donor) {
				$donorArray[] = $donor['name'];
			}
			$userNameArray[] = ' ' . join(',', $donorArray) . ' ($' . $amount . ')';
		}
	}
	if ($showSubscriber) {
		foreach ($subscribers as $subscriber) {
			$userNameArray[] = ' ' . $subscriber['display_name'] . ' (Subscribed)';
		}
	}

	if ($showFollower) {
		foreach ($followers as $follower) {
			$userNameArray[] = ' ' . $follower['display_name'] . ' (Followed)';
		}
	}

?>
<? if ( count($userNameArray) > 0 ): ?>
	<?= join(', ', $userNameArray); ?>
<? else: ?>
	<?= $emptyActivityMessage; ?>
<? endif; ?>
