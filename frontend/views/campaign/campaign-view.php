<?

use common\models\Campaign;
use common\models\Donation;
use yii\db\Query;

$this->registerJSFile('@web/js/campaign/donation-table.js',  \common\assets\CommonAsset::className());

$user = \Yii::$app->user->identity;
$donations = $campaign->getDonations()->all();

$uniqueDonations = $campaign->getDonations()->count('DISTINCT email');

$connection = \Yii::$app->db;
$sql = 'SELECT id, SUM(amount) sum FROM '.Donation::tableName().' WHERE (campaignId = '.$campaign->id.' or parentCampaignId = '.$campaign->id.') AND email <> "" AND paymentDate > 0 GROUP BY email ORDER BY sum DESC';
$command = $connection->createCommand($sql);
$topDonor = $command->queryScalar();
$topDonorName = ($topDonor) ? Donation::findOne($command->queryScalar())->name : '';
$topDonationId = $campaign->getDonations()->orderBy('amount DESC')->select('id')->scalar();
$topDonationName = ($topDonationId) ? Donation::findOne($topDonationId)->name : '';
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

<section class="campaings-view-wrap">

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

                <? if ($campaign->status != Campaign::STATUS_DELETED): ?>
                <li>
                    <a href="app/campaign" onclick="return campaignChangeStatus(<?= $campaign->id ?>, '<?= Campaign::STATUS_DELETED ?>')">
                        <i class="icon icon-remove"></i>
                        <!-- Remove -->
                    </a>
                </li>
                <? endif ?>

                <? if ($campaign->status != Campaign::STATUS_PENDING): ?>
                <li>
                    <a href="app/campaign" onclick="return campaignChangeStatus(<?= $campaign->id ?>,  '<?= Campaign::STATUS_PENDING ?>')">
                        <i class="icon icon-archive"></i>
                        <!-- Archive -->
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

            <ul class="campaign-buttons">

                <li>
                    <? /* $campaign->user and $user can be different because of parent campaigns*/ ?>
                    <a href='<?= $campaign->user->getUrl() . $campaign->alias ?>'>
                        <!-- View -->
                        View Campaign
                    </a>
                </li>

                <li>
                    <a href="https://github.com/noahshrader/pullr/blob/master/docs/SHORTCODES.md">
                        <!-- Shortcodes -->
                        XML
                    </a>
                </li>

            </ul>

        </div>

        <div class="clearfix"></div>
    
    </div>
        
    <div class="campaign-view-selected" data-id="<?= $campaign->id ?>">

         <h1> <?= ($campaign->name)?$campaign->name:'New campaign' ?></h1>

        <? if ($campaign->type != Campaign::TYPE_PERSONAL_TIP_JAR && $campaign->startDate && $campaign->endDate): ?>
        <h3 class="campaign-date"><?= date('M j Y', $campaign->startDate) ?> - <?= date('M j Y', $campaign->endDate) ?></h3>
        <? endif ?>


        <section class="donation-overview">


                    <div class='col-xs-3 d-figure raised-total'>
                        <span>$<?= number_format($campaign->amountRaised) ?></span>Raised
                    </div>
                    <div class='col-xs-3 d-figure'>
                        <span>$<?= number_format($campaign->goalAmount) ?></span>Goal
                    </div>
                    <div class='col-xs-3 d-figure'>
                        <span><?= sizeof($donations) ?></span>Donations
                    </div>
                    <div class='col-xs-3 d-figure'>
                        <span><?= $uniqueDonations ?></span>Unique
                    </div>
                
                    <div class="clearfix"></div>

                    <div class='col-xs-6 top-donor'>
                        <h4><?= $topDonorName ?></h4>
                        <h5>Top Donor</h5>
                    </div>
                    <div class='col-xs-6 top-donor'>
                        <h4><?= $topDonationName ?></h4>
                        <h5>Top Donation</h5>
                    </div>
                    
                    <div class="clearfix"></div>

                    <? $progress = ($campaign->amountRaised / max(1, $campaign->goalAmount))*100;
                    ?>
                    <div class="progress">
                        <div class="progress-line" role="progressbar" aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$progress ?>%;">
                            <?= round($progress) ?>%
                        </div>
                    </div>

        </section>
        
        <?= $this->render('donations-table', [
                    'donations' => $donations
                ]); ?>     
    </div>
</section>