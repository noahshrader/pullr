<?
    use common\models\Campaign;
?>

<section class="<?= ($campaign->type == Campaign::TYPE_PERSONAL_FUNDRAISER) ? 'tip-jar' :'events-form' ?>">
    <div class="donation-form-header">
        <h2 class="main-title"><?= $campaign->name ?></h2>
        <? if ($campaign->type != Campaign::TYPE_PERSONAL_FUNDRAISER): ?>
            <?
            $charityName = '';
            if ($campaign->donationDestination == Campaign::DONATION_CUSTOM_FUNDRAISER){
                $charityName = $campaign->customCharity;
            }
            if ($campaign->donationDestination == Campaign::DONATION_PARTNERED_CHARITIES && $campaign->charityId){
                $charityName = $campaign->charity->name;
            }
            ?>

            <? if ($charityName):?>
                <h3 class="charity-name">for <span><?= $charityName ?></span>
                <? if ($campaign->donationDestination == Campaign::DONATION_PARTNERED_CHARITIES): ?>
                    <a class="approved">
                        <i class="icon icon-check-round-fill"></i>
                        <span class="approved-info">This is a validated organization.</span>
                    </a>
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
    <iframe src="<?= $url; ?>" class="payment" style="height: 720px;"></iframe>
    <a class="back" href="<?= $back_url; ?>"><i class="icon icon-back"></i>Back</a>
</section>