<? if (count($donors) > 0 || ($showSubscriber && count($subscribers) > 0 ) || ($showFollower && count($followers) > 0)): ?>
	<? foreach($donors as $index => $donor): ?>                    
	     <?=$donor['name']; ?> ($<?=number_format($donor['amount'], 2);?>)<? if ($index < count($donors) - 1 || ($showSubscriber && count($subscribers) > 0) || ($showFollower && count($followers) > 0)):?>,<? endif; ?>
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
	<?= $emptyActivityMessage; ?>
<? endif; ?>