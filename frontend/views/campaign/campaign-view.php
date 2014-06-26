<?

use common\models\Campaign;
use common\assets\DataTableAsset;
use common\models\Donation;
use yii\db\Query;
DataTableAsset::register($this);
$this->registerJSFile('@web/js/campaign/donation-table.js', DataTableAsset::className());

$user = \Yii::$app->user->identity;
$donations = $campaign->donations;

$uniqueDonations = $campaign->getDonations()->count('DISTINCT email');

$connection = \Yii::$app->db;
$sql = 'SELECT id, SUM(amount) sum FROM '.Donation::tableName().' WHERE campaignId = '.$campaign->id.' AND email <> "" AND paymentDate > 0 GROUP BY email ORDER BY sum DESC';
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

        <ul class="campaign-quick-links col-md-6">

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
                    <i class="icon icon-check2"></i>
                    <!-- Restore -->
                </a>
            </li>
            <? endif ?>

        </ul>

        <ul class="campaign-buttons col-md-6">

            <li>
                <a href='<?= $user->getUrl() . $campaign->alias ?>'>
                    <i class="icon icon-view"></i>
                    <!-- View -->
                    View Campaign
                </a>
            </li>

            <li>
                <a href="https://github.com/noahshrader/pullr/blob/master/docs/SHORTCODES.md">
                    <i class="icon icon-code"></i>
                    <!-- Shortcodes -->
                    XML
                </a>
            </li>

        </ul>

        <div class="clearfix"></div>
    
    </div>
        
    <div class="campaign-view-selected" data-id="<?= $campaign->id ?>">

         <h1> <?= ($campaign->name)?$campaign->name:'New campaign' ?></h1>

        <? if ($campaign->type != Campaign::TYPE_PERSONAL_TIP_JAR && $campaign->startDate && $campaign->endDate): ?>
        <div class="text-center"><?= date('M j Y', $campaign->startDate) ?> - <?= date('M j Y', $campaign->endDate) ?></div>
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

                    <div class='col-xs-6'>
                        <div><?= $topDonorName ?></div>
                        <div>Top Donor</div>
                    </div>
                    <div class='col-xs-6'>
                        <div><?= $topDonationName ?></div>
                        <div>Top Donation</div>
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