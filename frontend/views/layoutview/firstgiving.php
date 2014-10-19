<?
    use common\models\Campaign;
?>

<section class="<?= ($campaign->type == Campaign::TYPE_PERSONAL_FUNDRAISER) ? 'tip-jar' :'events-form' ?>">
    <div class="donation-form-header">
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
                    <span class="approved icon-check-round-fill"></span>
                </h3>
                <? endif ?>
            <? endif ?>
        <? endif; ?>
    </div>

    <div class="spinner-wrap">
        <div class="spinner">
            <div class="rect1"></div>
            <div class="rect2"></div>
            <div class="rect3"></div>
            <div class="rect4"></div>
            <div class="rect5"></div>
        </div>
    </div>
    <!-- Form -->
    <iframe src="<?= $url; ?>" class="payment" style="height: 720px; min-height: 100px;"></iframe>
    <a class="back" href="<?= $back_url; ?>"><i class="icon icon-back"></i>Back</a>
    <span class="processed">processed by FirstGiving</span>
</section>