<?
use common\models\Campaign;
use common\models\Plan;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $form ActiveForm */

$parentCampaigns = \Yii::$app->user->identity
    ->getCampaigns()
    ->andWhere('id <> :campaignId')
    ->andWhere(['type' => Campaign::TYPE_CHARITY_FUNDRAISER])
    ->andWhere(['tiedToParent' => 0])
    ->addParams([':campaignId' => $campaign->id])
    ->all();

$isTied = $campaign->tiedToParent && (sizeof($parentCampaigns) > 0);
$this->registerJsFile('@web/js/campaign/firstgiving.js', [
    'depends' => common\assets\CommonAsset::className(),
]);
$firstGiving = $campaign->getFirstGiving();
?>
<div id="campaign-edit-team">
    <div class="center module-inner">
        <h5><i class="icon mdi-social-group"></i>Team Fundraising</h5>
        <p class="team-notice">Team fundraising is only available for charity fundraisers.</p>
        <? if (\Yii::$app->user->identity->getPlan()==Plan::PLAN_PRO): ?>
        <div class="form-group" id="teamQuestion">
            <label>Enable Team Fundraising <i class="icon mdi-action-help" data-toggle="tooltip" data-placement="right" title="Turn on to allow other Pullr users to join and contribute to this campaign."></i></label>
            <?= $form->field($campaign, 'teamEnable')->label(false)->checkbox([], false); ?>
        </div>
        <? endif; ?>

        <!-- Parent Campaigns -->
        <? if (sizeof($parentCampaigns) > 0): ?>
            <? 
            $keyValues = [ 0 => ''];
            foreach ($parentCampaigns as $parentCampaign){
                $keyValues[$parentCampaign->id] = $parentCampaign->name;
            }
        ?>
        <div id="tieCampaignContainer">
            <label>Connect to Another Campaign <i class="icon mdi-action-help" data-toggle="tooltip" data-placement="right" title="Turn on if you want this campaign to contribute to another campaign."></i></label>
            <?= $form->field($campaign, 'tiedToParent')->label(false)->checkbox([], false); ?>
            <div class="form-group field-campaign-parentcampaignid highlight-wrap">
                <label>Fundraiser Campaign <i class="icon mdi-action-help" data-toggle="tooltip" data-placement="right" title="Select the campaign to which you want to contribute."></i></label>
                <?= Html::activeDropDownList($campaign, 'parentCampaignId', $keyValues, ['class' => 'select-block']) ?>
            </div>
        </div>
        <? endif; ?>
    </div>
    <!-- Team Invites -->
    <div id="teamInvites" class="module-inner">
        <h5><i class="icon mdi-social-group-add"></i>Team Invites</h5>
        <? if ($campaign->isNewRecord): ?>
            <div class="label label-danger">Save campaign before adding emails</div>
        <? else: ?>
        <div id="campaign-invites">
            <div id="addCampaingInviteInfo" class="label label-danger"></div>
            <label>Invite Pullr users to your team <i class="icon mdi-action-help" data-toggle="tooltip" data-placement="right" title="Type in your Pullr invitee's email address they used to connect with Twitch. Support for channel names coming soon."></i></label>
            <div class="combined-form-wrap form-group">
                <input type="text" id="addCampaignInvite" placeholder="Add a Twitch Email Address" class="form-control">
                <div class="help-block hide">The invitee needs to be a member of Pullr</div>
                <a onclick="addNewCampaignInvite()" class="icon mdi-content-add-circle"></a>
            </div>
            <div id="campaignInvitesUsers" class="team-list highlight-wrap"></div>
        </div>
        <? endif ?>
    </div>
</div>