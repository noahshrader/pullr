<?

use common\models\Campaign;
use common\models\Donation;

/**
 * @var $this View
 * @var $campaign Campaign
 */
$this->registerJSFile('@web/js/campaign/donation-table.js',  \common\assets\CommonAsset::className());

$user = \Yii::$app->user->identity;
$donations = $campaign->getDonations()->all();

$topDonors = Donation::getTopDonorsForCampaigns([$campaign], 1, false);
$topDonorText = sizeof($topDonors) > 0 ? $topDonors[0]['name'].' ($'.number_format($topDonors[0]['amount']).')' : '';
$topDonation = Donation::getTopDonation([$campaign]);
$topDonationText = ($topDonation) ? $topDonation->name . ' ($'.number_format($topDonation->amount).')': '';
?>

<script type="text/javascript">
    function campaignChangeStatus(id, status){
        if (status!= '<?= Campaign::STATUS_DELETED ?>' || confirm('Are you sure to remove campaign?')){
            $.get('app/campaign/status', {id: id, status: status}, function(){
                    location.href='app/campaign';    
                }
            );
        }
        return false;
    }
</script>

<section class="campaigns-view-wrap">
    <div class="campaign-actions">
        <div class="col-md-6 campaign-nav">
            <? if (!$campaign->isParentForCurrentUser()): ?>
            <ul class="campaign-quick-links">
                <li class="active">
                    <a href="app/campaign/view?id=<?= $campaign->id ?>">
                        <i class="icon icon-piechart"></i>
                        <!-- Overview -->
                    </a>
                </li>
                <li>
                    <a href="app/campaign/edit?id=<?= $campaign->id ?>">
                        <i class="icon icon-edit"></i>
                        <!-- Edit -->
                    </a>
                </li>
                <li>
                    <? /* $campaign->user and $user can be different because of concept of parent campaigns*/ ?>
                    <a href='<?= $campaign->user->getUrl() . $campaign->alias ?>/json' target="_blank">
                        <i class="icon icon-code"></i>
                    </a>
                </li>
                <? if ($campaign->status != Campaign::STATUS_PENDING): ?>
                <li>
                    <a href="app/campaign" onclick="return campaignChangeStatus(<?= $campaign->id ?>,  '<?= Campaign::STATUS_PENDING ?>')">
                        <i class="icon icon-archive"></i>
                        <!-- Archive -->
                    </a>
                </li>
                <? endif ?>
                <? if ($campaign->status != Campaign::STATUS_DELETED): ?>
                <li>
                    <a href="app/campaign" onclick="return campaignChangeStatus(<?= $campaign->id ?>, '<?= Campaign::STATUS_DELETED ?>')">
                        <i class="icon icon-remove"></i>
                        <!-- Remove -->
                    </a>
                </li>
                <? endif ?>
                <? if ($campaign->status != Campaign::STATUS_ACTIVE): ?>
                <li>
                    <a href="app/campaign" onclick="return campaignChangeStatus(<?= $campaign->id ?>,  '<?= Campaign::STATUS_ACTIVE ?>')">
                        <i class="icon icon-recover"></i>
                        <!-- Restore -->
                    </a>
                </li>
                <? endif ?>
            </ul>
            <? endif ?>
        </div>
        <div class="col-md-6 campaign-nav">
            <? /* $campaign->user and $user can be different because of concept of parent campaigns*/ ?>
            <a class="view-campaign" href='<?= $campaign->user->getUrl() . $campaign->alias ?>' target="_blank">View Campaign</a>
        </div>
    </div>
    <div class="campaign-view-wrap" data-id="<?= $campaign->id ?>">
         <h1>
            <?= ($campaign->name)?$campaign->name:'New campaign' ?>
            <? if ($campaign->type != Campaign::TYPE_PERSONAL_TIP_JAR && $campaign->startDate && $campaign->endDate): ?>
            <span class="campaign-date"><?= date('M j, Y', $campaign->startDate) ?> - <?= date('M j, Y', $campaign->endDate) ?></span>
            <? endif ?>
         </h1>
        <section class="stats-overview">
            <div class="main-values">
                <div class='stats-box col-xs-3 raised-total'>
                    <h2>$<?= number_format($campaign->amountRaised) ?></h2>
                    <h5>Raised</h5>
                </div>
                <div class='stats-box col-xs-3 campaign-goal'>
                    <h2>$<?= number_format($campaign->goalAmount) ?></h2>
                    <h5>Goal</h5>
                </div>
                <div class="progress-wrap">
                    <? $progress = ($campaign->amountRaised / max(1, $campaign->goalAmount))*100;
                    ?>
                    <div class="progress">
                        <div class="progress-line" role="progressbar" aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$progress ?>%;"></div>
                    </div>
                </div>
            </div>
            <div class='stats-box col-xs-3 total-donations'>
                <h2><?= $campaign->numberOfDonations ?></h2>
                <h5>Donations</h5>
            </div>
            <div class='stats-box col-xs-3 total-donors'>
                <h2><?= $campaign->numberOfUniqueDonors ?></h2>
                <h5>Donors</h5>
            </div>
            <div class="clearfix"></div>

            <div class='stats-box col-xs-6 top-donor'>
                <h3><?= $topDonorText ?></h3>
                <h5>Top Donor</h5>
            </div>
            <div class='stats-box col-xs-6 top-donation'>
                <h3><?= $topDonationText ?></h3>
                <h5>Top Donation</h5>
            </div>
            <div class="clearfix"></div>
        </section>
        <?= $this->render('campaign-view-childs', [
            'campaign' => $campaign
        ]);?>
        
        <?= $this->render('donations-table', [
            'donations' => $donations
        ]); ?>     
    </div>
</section>