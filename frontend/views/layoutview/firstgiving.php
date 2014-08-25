<?
    use common\models\Campaign;
?>
<section class="<?= ($campaign->type == Campaign::TYPE_PERSONAL_FUNDRAISER) ? 'tip-jar' :'events-form' ?>">
    <h1 class="campaign-name"><?= $campaign->name ?></h1>
    <? if ($campaign->type != Campaign::TYPE_PERSONAL_FUNDRAISER): ?>
        <?
        $charityName = '';
        if ($campaign->donationDestination == Campaign::DONATION_CUSTOM_FUNDRAISER){
            $charityName = $campaign->customCharity;
        }
        if ($campaign->donationDestination == Campaign::DONATION_PREAPPROVED_CHARITIES && $campaign->charityId){
            $charityName = $campaign->charity->name;
        }
        ?>
        <? if ($charityName):?>
            <h3 class="charity-name">for <span><?= $charityName ?></span>
            <? if ($campaign->donationDestination == Campaign::DONATION_PREAPPROVED_CHARITIES): ?>
                <span class="approved icon-check2"></span></h3>
            <? endif ?>
        <? endif ?>
    <? endif; ?>
    <iframe src="<?= $url; ?>" class="payment" style="height: 720px; min-height: 100px;"></iframe>
    <a href="<?= $back_url; ?>">back</a>
</section>