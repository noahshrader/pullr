<?php
use common\components\PullrUtils;

$this->registerJSFile('@web/js/campaign/donation-table.js', [
    'depends' => common\assets\CommonAsset::className(),
]);

$user = \Yii::$app->user->identity;

?>

<section class="donor-view-wrap">
    <div id="content" class="adv red pane" data-email="<?= htmlspecialchars($email) ?>" >
        <div class="content-wrap">
            <h1 class="donor-name"> <?= htmlspecialchars($name) ?>
                <span class="donor-email"><?= htmlspecialchars($email) ?></span>
            </h1>
            <section class="stats-overview module">
                <div class='stats-box col-xs-4'>
                    <h2>$<?= PullrUtils::formatNumber($totalDonated, 2) ?></h2>
                    <span>Total Donated</span>
                </div>
                <div class='stats-box col-xs-4'>
                    <h2>$<?= PullrUtils::formatNumber($topDonation, 2) ?></h2>
                    <span>Top Donation</span>
                </div>
                <div class='stats-box col-xs-4'>
                    <h2><?= sizeof($donations) ?></h2>
                    <span>Donations</span>
                </div>
                <div class="clearfix"></div>
            </section>
            <section class="module table">
                <div class="spinner-wrap">
                    <div class="spinner">
                        <div class="rect1"></div>
                        <div class="rect2"></div>
                        <div class="rect3"></div>
                        <div class="rect4"></div>
                        <div class="rect5"></div>
                    </div>
                </div>
                <?= $this->render('donations-table-for-donor', [
                    'donations' => $donations
                ]); ?>
            </section>
        </div>
    </div>
</section>