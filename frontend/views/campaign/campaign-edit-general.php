<?
use common\models\Campaign;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $form ActiveForm */


$parentCampaigns = \Yii::$app->user->identity->getParentCampaigns()->all();
$isTied = $campaign->tiedToParent && (sizeof($parentCampaigns) > 0);

$this->registerJsFile('@web/js/campaign/firstgiving.js', common\assets\CommonAsset::className());

$firstGiving = $campaign->getFirstGiving();
?>
<div id="collapseOne" class="panel-collapse collapse in <?= $isTied ? 'isTied' : '' ?>">
        <!-- Campaign Name -->
        <div class="form-group">
            <label>Campaign Name</label>
            <?= $form->field($campaign, 'name', ['autoPlaceholder' => true]); ?>
        </div>

        <!-- Campaign Description -->
        <div class="form-group">
            <label>Campaign Description</label>
            <?= $form->field($campaign, 'description', ['autoPlaceholder' => true])->textarea(['maxlength' => Campaign::DESCRIPTION_MAX_LENGTH, 'rows' => 6]); ?>
        </div>

        <!-- Campaign Type -->
        <div class="form-group field-campaign-type">
            <label>Select a Campaign Type</label>
            <i class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="top" title="Some tooltip here."></i>
            <?= Html::activeDropDownList($campaign, 'type', array_combine(Campaign::$TYPES, Campaign::$TYPES), ['class' => 'select-block']) ?>
        </div>

        <!-- Campaign Dates/Times -->
        <div id="startEndContainer">
            <? $tooltip = '<i class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="left" title="Start date tooltip"></i>'; ?>
             <?= $form->field($campaign, 'startDate')->label("Start Date/Time $tooltip")->input('datetime-local'); ?>
            <? $tooltip = '<i class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="left" title="End date tooltip"></i>'; ?>
             <?= $form->field($campaign, 'endDate')->label("End Date/Time $tooltip")->input('datetime-local'); ?>
        </div>

        <!-- Parent Campaigns -->
        <? if (sizeof($parentCampaigns) > 0): ?>
            <? 
            $keyValues = [ 0 => ''];
            foreach ($parentCampaigns as $parentCampaign){
                $keyValues[$parentCampaign->id] = $parentCampaign->name;
            }
            ?>
            <div id="tieCampaignContainer">
                <?= $form->field($campaign, 'tiedToParent')->checkbox([], false); ?>
                 <div class="form-group field-campaign-parentcampaignid">
                    <label>Fundraiser Campaign</label>
                    <?= Html::activeDropDownList($campaign, 'parentCampaignId', $keyValues, ['class' => 'select-block']) ?>
                </div>
            </div>
        <? endif; ?>
        <div id="notTiedCampaignContainer">

        <!-- Campaign Goal Amount -->
        <div class="form-group">
            <label>Goal Amount</label>
            <?= $form->field($campaign, 'goalAmount', ['autoPlaceholder' => true]); ?>
        </div>

        <!-- Personal Fundraiser PayPal -->
        <div class="form-group">
            <?= $form->field($campaign, 'paypalAddress', ['autoPlaceholder' => true]); ?>
        </div>

        <!-- Donation Destination (Charity Dropdown / Custom Charity) -->
        <div id="donationDestination" data-donationDestination="<?= $campaign->donationDestination?>">
            <label> Donation Destination 
                <i class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="left" title="Select the donation destination."></i>
            </label>
            <div class="form-group field-campaign-donationDestination">
                <?= Html::activeDropDownList($campaign, 'donationDestination', array_combine(Campaign::$DONATION_DESTINATIONS, Campaign::$DONATION_DESTINATIONS), ['class' => 'select-block']) ?>
                <?= Html::error($campaign, 'donationDestination', ['class' => 'help-block']) ?>
            </div>
            <?/*
            <div class='preapprovedCharity'>
                <?= $form->field($campaign, 'charityId')->hiddenInput()->label(null, ['style' => 'display:none'])?>
                <div class='charity-name <? if (!$campaign->charityId) { echo 'hidden';} ?>'>
                    <label>
                        Selected charity:
                    </label>
                    <span><?= $campaign->charity?$campaign->charity->name:''?></span>
                </div>

                <button class="btn btn-primary" type="button" onclick="campaignChooseCharity()">Choose a charity</button>
            </div>
            */?>
            <div class="preapprovedCharity highlight-wrap">
                <h4>Choose from one of our partnered organizations</h4>
                <?= Html::input('hidden', 'firstgiving', $firstGiving ? $firstGiving->organization_uuid : null, ['id' => 'firstgiving', 'class' => 'select-block']); ?>
            </div>
            <div class="customCharity highlight-wrap">
                <h4>Support your own charity</h4>
                <div class="form-group">
                    <label>Charity Name</label>
                    <?= $form->field($campaign, 'customCharity', ['autoPlaceholder' => true]); ?>
                </div>
                <div class="form-group">
                    <label>Charity PayPal Address</label>
                    <?= $form->field($campaign, 'customCharityPaypal', ['autoPlaceholder' => true]); ?>
                </div>
                <div class="form-group">
                    <label>Campaign Description</label>
                    <?= $form->field($campaign, 'customCharityDescription', ['autoPlaceholder' => true])->textarea(); ?>
                </div>
            </div>
        </div>
        </div>
        <div class="clearfix"></div>
    </div>