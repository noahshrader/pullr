<?
$this->registerJSFile('@web/js/campaign/donation-table.js', DataTableAsset::className());

$user = \Yii::$app->user->identity;

?>

<section class="donor-view-wrap">
    <div class="donor-view" data-email="<?= htmlspecialchars($email) ?>" >
        <h1 class="text-center donor-name"> <?= htmlspecialchars($name) ?></h1>
        <div class="text-center donor-email"><?= htmlspecialchars($email) ?></div>
        
        <section class="donation-overview">
                    <div class='col-xs-4 d-figure'>
                        <span>$<?= number_format($totalDonated) ?></span>Total Donated
                    </div>
                    <div class='col-xs-4 d-figure'>
                        <span>$<?= number_format($topDonation) ?></span>Top Donation
                    </div>
                    <div class='col-xs-4 d-figure'>
                        <span><?= sizeof($donations) ?></span>Donations
                    </div>
        </section>
        
         <?= $this->render('donations-table-for-donor', [
                    'donations' => $donations
                ]); ?>    
    </div>
</section>