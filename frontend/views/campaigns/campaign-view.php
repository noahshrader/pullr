<?php
use common\models\Campaign;
use common\models\Donation;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/**
 * @var $this View
 * @var $campaign Campaign
 */
$this->registerJSFile('@web/js/campaign/donation-table.js',  [
    'depends' => common\assets\CommonAsset::className(),
]);

$user = \Yii::$app->user->identity;
$donations = $campaign->getDonations()->all();

$topDonors = Donation::getTopDonorsForCampaigns([$campaign], 1, true);
$topDonorText = sizeof($topDonors) > 0 ? $topDonors[0]['name'] . '<span>$' . \common\components\PullrUtils::formatNumber($topDonors[0]['amount'], 2) . '</span>': '';
$topDonation = Donation::getTopDonation([$campaign]);
$topDonationText = ($topDonation) ? (!empty($topDonation->nameFromForm) ? $topDonation->nameFromForm : 'Anonymous')  . '<span>' . '$' . \common\components\PullrUtils::formatNumber($topDonation->amount, 2) . '' . '</span>': '';
$charityName = '';
if ($campaign->donationDestination == Campaign::DONATION_CUSTOM_FUNDRAISER){
     $charityName = $campaign->customCharity;
} 
if ($campaign->donationDestination == Campaign::DONATION_PARTNERED_CHARITIES && $campaign->charityId){
     $charityName = $campaign->charity->name;
} 
?>

<script type="text/javascript">
    function campaignChangeStatus(id, status){
        if (status!= '<?= Campaign::STATUS_DELETED ?>' || confirm('Are you sure to remove your campaign?')){
            $.get('app/campaigns/status', {id: id, status: status}, function(){
                    location.href='app/campaigns';    
                }
            );
        }
        return false;
    }
</script>

<section class="campaigns-view-wrap">
    <div id="content" class="adv pane" data-id="<?= $campaign->id ?>">
        <div class="content-wrap">
            <div class="view-actions">
                <div class="campaign-nav">
                    <? if (!$campaign->isParentForCurrentUser()): ?>
                    <ul class="campaign-quick-links dropdown">
                        <li>
                            <a class="actions-toggle mdi-navigation-menu"></a>
                            <ul>
                                <li class="active cf">
                                    <a href="app/campaigns/view?id=<?= $campaign->id ?>">
                                        <!-- Overview -->
                                        Overview
                                    </a>
                                </li>
                                <li class="cf">
                                    <a href="app/campaigns/edit?id=<?= $campaign->id ?>">
                                        <!-- Edit -->
                                        Edit
                                    </a>
                                </li>
                                <li class="cf">
                                    <a href='<?= $campaign->user->getUrl() . $campaign->alias ?>/donate' target="_blank">
                                        Donation Form
                                    </a>
                                </li>
                                <li class="cf">
                                    <a href='<?= $campaign->user->getUrl() . $campaign->alias ?>/json' target="_blank">
                                        JSON
                                    </a>
                                </li>
                                <li class="cf">
                                    <a class="disabled">
                                        Widgets
                                    </a>
                                </li>
                                <? if ($campaign->status != Campaign::STATUS_PENDING): ?>
                                <li class="cf">
                                    <a href="app/campaigns" onclick="return campaignChangeStatus(<?= $campaign->id ?>,  '<?= Campaign::STATUS_PENDING ?>')">
                                        <!-- Archive -->
                                        Archive
                                    </a>
                                </li>
                                <? endif ?>
                                <? if ($campaign->status != Campaign::STATUS_ACTIVE): ?>
                                <li class="cf">
                                    <a href="app/campaigns" onclick="return campaignChangeStatus(<?= $campaign->id ?>,  '<?= Campaign::STATUS_ACTIVE ?>')">
                                        <!-- Restore -->
                                        Restore
                                    </a>
                                </li>
                                <? endif ?>
                                <? if ($campaign->status != Campaign::STATUS_DELETED): ?>
                                <li class="cf">
                                    <a href="app/campaigns" onclick="return campaignChangeStatus(<?= $campaign->id ?>, '<?= Campaign::STATUS_DELETED ?>')">
                                        <!-- Remove -->
                                        Delete
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <? endif ?>
                    </ul>
                    <? endif ?>
                </div>
                <h4>
                    <a href="app/campaigns/view?id=<?= $campaign->id ?>"><?= ($campaign->name)?$campaign->name:'New Campaign' ?></a>
                    <span class="top-info">
                        <!--<span class="charity-name">
                            <?= $charityName ?>
                        </span>-->
                        <!--<? $date = (new DateTime())->setTimezone(new DateTimeZone(Yii::$app->user->identity->getTimezone())); ?>
                        <?= $date->setTimestamp($campaign->startDate)->format('M j, Y'); ?>
                        -
                        <?= $date->setTimestamp($campaign->endDate)->format('M j, Y'); ?>-->
                    </span>
                </h4>
                <? /* $campaign->user and $user can be different because of concept of parent campaigns*/ ?>
                <a class="view-campaign" href='<?= $campaign->user->getUrl() . urlencode($campaign->alias); ?>' target="_blank"><i class="icon mdi-action-visibility" title="View campaign page"></i></a>
            </div>
            <? if ($campaign->type != Campaign::TYPE_PERSONAL_FUNDRAISER && $campaign->startDate && $campaign->endDate): ?>
            <? endif ?>
            <section class="stats-overview main-values module">
                <div class='stats-box col-xs-3 raised-total'>
                    <h1>$<?= \common\components\PullrUtils::formatNumber($campaign->amountRaised, 2); ?></h1>
                    <span>Raised</span>
                </div>
                <div class='stats-box col-xs-3 campaign-goal'>
                    <h1>$<?= \common\components\PullrUtils::formatNumber($campaign->goalAmount, 2) ?></h1>
                    <span>Goal</span>
                </div>

                <? if($campaign->goalAmount > 0):?>
                <div class="progress-wrap">
                    <? $progress = ($campaign->amountRaised / max(1, $campaign->goalAmount))*100;
                    ?>
                    <div class="progress">
                        <div class="progress-line" role="progressbar" aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$progress ?>%;">
                            <span data-toggle="tooltip" data-placement="top" title="<?= round($progress) ?>%"></span>
                        </div>
                    </div>
                </div>
                <? endif;?>

            </section>
            <section class="stats-overview module">
                <div class='stats-box col-xs-6 total-donations'>
                    <h2><?= $campaign->numberOfDonations ?></h2>
                    <span>Donations</span>
                </div>
                <div class='stats-box col-xs-6 total-donors'>
                    <h2><?= $campaign->numberOfUniqueDonors ?></h2>
                    <span>Donors</span>
                </div>
                <div class='stats-box col-xs-6 top-donation'>
                    <h3><?= $topDonationText ?></h3>
                    <span>Top Donation</span>
                </div>
                <div class='stats-box col-xs-6 top-donor'>
                    <h3><?= $topDonorText ?></h3>
                    <span>Top Donor</span>
                </div>
                <div class="clearfix"></div>
            </section>
            <section class="campaign-table module table">
                <?= $this->render('campaign-view-childs', [
                    'campaign' => $campaign
                ]);?>
                <div class="spinner-wrap">
                    <div class="spinner">
                        <div class="rect1"></div>
                        <div class="rect2"></div>
                        <div class="rect3"></div>
                        <div class="rect4"></div>
                        <div class="rect5"></div>
                    </div>
                </div>
                <?= $this->render('donations-table', [
                    'donations' => $donations,
                    'campaignId' => $campaign->id
                ]); ?>
            </section>
        </div>
    </div>
</section>

<? $manualDonation = new \frontend\models\site\ManualDonation(); ?>

<!-- Modal -->
<div class="modal fade" id="manualDonationModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog module">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h5 class="module-title">Add Donation</h5>
        <div class="modal-content">
            <? $form = ActiveForm::begin([
                'action'=> '/app/campaigns/manualdonation'
            ]) ?>
            <div class="modal-body">
                <?= $form->field($manualDonation, 'name')->input('text')->label('Donor Name');?>
                <?= $form->field($manualDonation, 'email')->input('text')->label('Email');?>
                <div class="manualdonation-amount form-group required">
                    <label for="masked-amount" class="control-label">Amount</label>
                    <?= MaskedInput::widget([
                        'name' => 'ManualDonation[amount]',
                        'value' => 1,
                        'options' => [
                            'class' => 'form-control',
                            'id' => 'masked-amount',
                            'placeholder' => 'Donation Amount'
                        ],
                        'clientOptions' => [
                            'value' => 1,
                            'prefix' => '$',
                            'alias' =>  'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true,
                            'autoUnmask' => true,
                            'rightAlign' => false,
                            'allowMinus' => false,
                            'allowPlus' => false,
                            'removeMaskOnSubmit' => true
                        ],
                    ]) ?>
                </div>
                <?= $form->field($manualDonation, 'dateCreated')->label("Date")->input('datetime-local', ['value' => strftime('%Y-%m-%dT%H:%M', time())]); ?>
                <?= $form->field($manualDonation, 'comments')->label('Comments')->textarea();?>
                <?= $form->field($manualDonation, 'campaignId')->hiddenInput(['value' => $campaign->id])->label(false);?>
            </div>
            <div class="modal-footer btn-container">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Add</button>
            </div>
            <? ActiveForm::end() ?>
        </div>
    </div>
</div>