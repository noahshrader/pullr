<? foreach($donors as $index => $donor): ?>                    
     <?=$donor['name']; ?> ($<?=number_format($donor['amount']);?>)<? if ($index < count($donors) - 1 || ($showSubscriber && count($subscribers) > 0) || ($showFollower && count($followers) > 0)):?>,<? endif; ?>
<? endforeach; ?>

<? if ($showSubscriber): ?>
    <? foreach ($subscribers as $index => $subscriber):?>
    <?=$subscribe['display_name']; ?> (Subscribed)<? if ($index < count($subscribers) - 1 || ($showFollower && count($followers) > 0)):?>,<? endif; ?>
    <? endforeach; ?>
<? endif;?>

<? if ($showFollower): ?>
    <? foreach($followers as $index => $follower): ?>
    <?=$follower['display_name']; ?> (Followed)<? if ($index < count($followers) - 1):?>,<? endif; ?>
    <? endforeach; ?>
<? endif; ?>