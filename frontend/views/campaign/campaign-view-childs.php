<? 
    $childCampaigns = $campaign->getChildCampaigns()->all(); 
?>

<? if (sizeof($childCampaigns) > 0) : ?>
<?
    $amountRaised = 0;
    foreach ($childCampaigns as $child){
        $amountRaised+=$child->amountRaised;
    }
?>
    <table class="campaign-view-childs">
        <thead>
        <tr>
            <th><?= sizeof($childCampaigns) ?> Active <span>$<?= number_format($amountRaised) ?></span></th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <? foreach ($childCampaigns as $child): ?>
        <tr>
            <td><?= $child->name ?></td> 
            <td>
                <? if ($child->startDate && $child->endDate): ?>
                    <?= date('M j Y', $child->startDate) ?> - <?= date('M j Y', $child->endDate) ?>
                <? endif ?>
            </td>
            <td>$<?= number_format($child->amountRaised) ?></td>
        </tr>
        <? endforeach; ?>
        </tbody>
    </table>
<? endif; ?>
