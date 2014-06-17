<?

use common\models\Campaign;
use common\assets\DataTableAsset;
DataTableAsset::register($this);
$this->registerJSFile('@web/js/campaign/donation-table.js', DataTableAsset::className());

$user = \Yii::$app->user->identity;
$donations = $campaign->donations;
?>
<section class="campaings-view-selected">
    <h1 class="text-center"><?= $campaign->name ?></h1>
    <? if ($campaign->type != Campaign::TYPE_PERSONAL_TIP_JAR && $campaign->startDate && $campaign->endDate): ?>
        <div class="text-center"><?= date('M j Y', $campaign->startDate) ?> - <?= date('M j Y', $campaign->endDate) ?></div>
<? endif ?>
    <div class="campaign-actions">
        <div class="row text-center"  >
            <a href='<?= $user->getUrl() . $campaign->alias ?>' class="col-xs-3">
                <i class="glyphicon glyphicon-search"></i>
                <br>
                View campaign
            </a>
            <a href="app/campaign/edit?id=<?= $campaign->id ?>" class="col-xs-3">
                <i class="glyphicon glyphicon-edit"></i>
                <br>
                Edit campaigned
            </a>
            <a href="app/campaign" class="col-xs-3"  onclick="return layoutRemove(<?= $campaign->id ?>)">
                <i class="glyphicon glyphicon-remove"></i>
                <br>
                Campaign remove
            </a>
            <a href="https://github.com/noahshrader/pullr/blob/master/docs/SHORTCODES.md" class="col-xs-3">
                <br>
                Shortcodes
            </a>
        </div>
    </div>

    <table id="campaign-donations" class="display" cellspacing="0" width="100%">
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
                       <?= date('M j Y', $donation->paymentDate) ?>
                    </td>
                    <td>
                        <?= $donation->name ?>
                    </td>
                    <td>
                        $<?= number_format($donation->amount) ?>
                    </td>
                    <td>
                        <?= $donation->comments ? 'Yes' : 'No' ?>
                    </td>
                </tr>
            <? endforeach; ?>
        </tbody>
    </table>
</section>