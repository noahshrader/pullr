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
$sql = 'SELECT name, SUM(amount) sum FROM '.Donation::tableName().' WHERE campaignId = '.$campaign->id.' AND email <> "" AND paymentDate > 0 GROUP BY email ORDER BY sum DESC';
$command = $connection->createCommand($sql);
$topDonorName = $command->queryScalar();
$topDonationName = $campaign->getDonations()->orderBy('amount DESC')->select('name')->scalar();
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

            <ul class="campaign-actions">

                <li>
                    <a href="app/campaign/view?id=<?= $campaign->id ?>">
                        <i class="glyphicon glyphicon-edit"></i>
                        <br>
                        Overview
                    </a>
                </li>

                <li>
                    <a href="app/campaign/edit?id=<?= $campaign->id ?>">
                        <i class="glyphicon glyphicon-edit"></i>
                        <br>
                        Edit
                    </a>
                </li>
                <? if ($campaign->status != Campaign::STATUS_PENDING): ?>
                    <li>
                        <a href="app/campaign" onclick="return campaignChangeStatus(<?= $campaign->id ?>,  '<?= Campaign::STATUS_PENDING ?>')">
                            <i class="glyphicon glyphicon-book"></i>
                            <br>
                            Archive
                        </a>
                    </li>
                <? endif ?>
                 <? if ($campaign->status != Campaign::STATUS_ACTIVE): ?>
                    <li>
                        <a href="app/campaign" onclick="return campaignChangeStatus(<?= $campaign->id ?>,  '<?= Campaign::STATUS_ACTIVE ?>')">
                            <i class="glyphicon glyphicon-ok"></i>
                            <br>
                            Restore
                        </a>
                    </li>
                <? endif ?>
                    <li>
                        <a href="https://github.com/noahshrader/pullr/blob/master/docs/SHORTCODES.md">
                            <br>
                            Shortcodes
                        </a>
                    </li>
                <? if ($campaign->status != Campaign::STATUS_DELETED): ?>
                    <li>
                        <a href="app/campaign" onclick="return campaignChangeStatus(<?= $campaign->id ?>, '<?= Campaign::STATUS_DELETED ?>')">
                            <i class="glyphicon glyphicon-remove"></i>
                            <br>
                            Remove
                        </a>
                    </li>
                <? endif ?>
                </ul>
        
    <div class="campaign-view-selected">

         <h1> <?= ($campaign->name)?$campaign->name:'New campaign' ?></h1>

        <? if ($campaign->type != Campaign::TYPE_PERSONAL_TIP_JAR && $campaign->startDate && $campaign->endDate): ?>
        <div class="text-center"><?= date('M j Y', $campaign->startDate) ?> - <?= date('M j Y', $campaign->endDate) ?></div>
        <? endif ?>

        <div class="center"><a href='<?= $user->getUrl() . $campaign->alias ?>'><i class="glyphicon glyphicon-search"></i>View Campaign</a></div>


        <section class="donation-overview">
            <div class='row'>
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
            </div>

            <div class='row campaign-overview-tops'>
                <div class='col-xs-6'>
                    <div><?= $topDonorName ?></div>
                    <div>Top Donor</div>
                </div>
                <div class='col-xs-6'>
                    <div><?= $topDonationName ?></div>
                    <div>Top Donation</div>
                </div>
            </div>


            <? $progress = ($campaign->amountRaised / max(1, $campaign->goalAmount))*100;
            ?>
            <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$progress ?>%;">
                    <?= round($progress) ?>%
                </div>
            </div>

        </section>
        
        <table id="campaign-donations" class="display donations-table extend" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>Donor Name</th>
                    <th>Amount</th>
                    <th>Comments</th>
                </tr>
            </thead>
            <tbody>
                <? foreach ($donations as $donation): ?>
                    <tr>
                        <td>
                           <?= date('M j, Y', $donation->paymentDate) ?>
                        </td>
                        <td>
                            <?= $donation->name ? $donation->name : 'Anonymous' ?>
                            <? if ($donation->email): ?>
                            &nbsp;
                                <i class="glyphicon glyphicon-plus-sign" data-container="body" data-toggle="popover" 
                                   data-placement="bottom" data-content="<?= $donation->email ?>"></i>
                            <? endif; ?>
                        </td>
                        <td>
                            $<?= number_format($donation->amount) ?>
                        </td>
                        <td>
                            <? if ($donation->comments): ?>
                            Yes &nbsp;
                                <i class="glyphicon glyphicon-plus-sign" data-container="body" data-toggle="popover" 
                                   data-placement="bottom" data-content="<?= $donation->comments ?>"></i>
                            <? else: ?>
                                No
                            <? endif ?>
                        </td>
                    </tr>
                <? endforeach; ?>
            </tbody>
        </table>
    </div>
</section>