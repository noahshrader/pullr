<?
$this->registerJSFile('@web/js/campaign/donation-table.js', \common\assets\CommonAsset::className());

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
                    <h2>$<?= number_format($totalDonated) ?></h2>
                    <h5>Total Donated</h5>
                </div>
                <div class='stats-box col-xs-4'>
                    <h2>$<?= number_format($topDonation) ?></h2>
                    <h5>Top Donation</h5>
                </div>
                <div class='stats-box col-xs-4'>
                    <h2><?= sizeof($donations) ?></h2>
                    <h5>Donations</h5>
                </div>
                <div class="clearfix"></div>
            </section>
            <section class="module">
                <?= $this->render('donations-table-for-donor', [
                    'donations' => $donations
                ]); ?>
            </section>
        </div>
    </div>
</section>