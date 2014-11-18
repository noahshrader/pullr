<? use common\components\PullrUtils; ?>
<? if ( count($groupDonors) > 0 || count($donors) > 0  || ($showSubscriber && count($subscribers) > 0 ) || ($showFollower && count($followers) > 0)): ?>
	<? if ( ! $groupUser): ?>
		<? foreach($donors as $index => $donor): ?>                    
		     <?=$donor['name']; ?> ($<?= PullrUtils::formatNumber($donor['amount']);?>)<? if ($index < count($donors) - 1 || ($showSubscriber && count($subscribers) > 0) || ($showFollower && count($followers) > 0)):?>,<? endif; ?>
		<? endforeach; ?>

		<? if ($showSubscriber): ?>
		    <? foreach ($subscribers as $index => $subscriber):?>
		    <?=$subscriber['display_name']; ?> (Subscribed)<? if ($index < count($subscribers) - 1 || ($showFollower && count($followers) > 0)):?>,<? endif; ?>
		    <? endforeach; ?>
		<? endif;?>

		<? if ($showFollower): ?>
		    <? foreach($followers as $index => $follower): ?>
		    <?=$follower['display_name']; ?> (Followed)<? if ($index < count($followers) - 1):?>,<? endif; ?>
		    <? endforeach; ?>
		<? endif; ?>
	<? else: ?>
		<? $groupIndex = 0; ?>
		<? foreach ($groupDonors as $amount => $donors): ?>
			<? foreach($donors as $index => $donor): ?>                    
			    <?=$donor['name']; ?> <? if ($index < count($donors) - 1) :?>,<? endif; ?>
			<? endforeach; ?> ($<?= PullrUtils::formatNumber($donor['amount']);?>)
			<? 
			$groupIndex++;
			if ($groupIndex < count($groupDonors)) echo ',';
			?>
		<? endforeach; ?>

		<? if (($showSubscriber && count($subscribers) > 0) || ($showFollower && count($followers) >0)) echo ','; ?>

		<? if ($showSubscriber && count($subscribers) > 0): ?>
		    <? foreach ($subscribers as $index => $subscriber):?>
		    <?=$subscriber['display_name']; ?> <? if ($index < count($subscribers) - 1 || ($showFollower && count($followers) > 0)):?>,<? endif; ?>
		    <? endforeach; ?> (Subscribed)
		<? endif;?>

		<? if ($showFollower && count($followers) > 0) echo ','; ?>

		<? if ($showFollower && count($followers) > 0): ?>
		    <? foreach($followers as $index => $follower): ?>
		    <?=$follower['display_name']; ?> <? if ($index < count($followers) - 1):?>,<? endif; ?>
		    <? endforeach; ?> (Followed)
		<? endif; ?>
	<? endif; ?>
<? else: ?>
	<?= $emptyActivityMessage; ?>
<? endif; ?>