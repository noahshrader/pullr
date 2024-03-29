<?php
    use common\components\PullrUtils;

    $childCampaigns = $campaign->getChildCampaigns()->all(); 
?>

<? if (sizeof($childCampaigns) > 0) : ?>
<?
    $amountRaised = 0;
    foreach ($childCampaigns as $child){
        $amountRaised+=$child->amountRaised;
    }
?>
<table class="campaign-view-childs dataTable">
    <thead>
    <tr>
        <th><?= sizeof($childCampaigns) ?> Connected Campaigns</th>
        <th></th>
        <th><span class="child-totals">$<?= PullrUtils::formatNumber($amountRaised, 2) ?></span></th>
    </tr>
    </thead>
    <tbody>
    <? foreach ($childCampaigns as $child): ?>
    <tr>
        <td><?= $child->name ?></td> 
        <td>
            <? if ($child->startDate && $child->endDate): ?>
                <? $date = (new DateTime())->setTimezone(new DateTimeZone(Yii::$app->user->identity->getTimezone())); ?>
                <?=$date->setTimestamp($child->startDate)->format('M j, Y'); ?> - <?=$date->setTimestamp($child->endDate)->format('M j, Y'); ?>
            <? endif ?>
        </td>
        <td class="raised">$<?= PullrUtils::formatNumber($child->amountRaised, 2) ?></td>
    </tr>
    <? endforeach; ?>
    </tbody>
</table>
<? endif; ?>
