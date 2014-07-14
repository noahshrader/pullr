<?
$this->registerJSFile('@web/js/campaign/donation-table.js', \common\assets\CommonAsset::className());

$user = \Yii::$app->user->identity;

?>

<section class="donor-view-wrap">
    <div class="donor-view" data-email="<?= htmlspecialchars($email) ?>" >
        <h1 class="text-center donor-name"> <?= htmlspecialchars($name) ?></h1>
        <h3 class="text-center donor-email"><?= htmlspecialchars($email) ?></h3>
        
        <section class="stats-overview">

            <div class='stats-box third'>
            <span>$<?= number_format($totalDonated) ?></span>Total Donated
            </div>

            <div class='stats-box third'>
            <span>$<?= number_format($topDonation) ?></span>Top Donation
            </div>

            <div class='stats-box third'>
            <span><?= sizeof($donations) ?></span>Donations
            </div>

            <div class="clearfix"></div>

        </section>
        
         <?= $this->render('donations-table-for-donor', [
                    'donations' => $donations
                ]); ?>    
    </div>
</section>