<?
use common\models\Campaign;
use common\models\Plan;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $form ActiveForm */

$parentCampaigns = \Yii::$app->user->identity
    ->getCampaigns()
    ->where('id <> :campaignId')
    ->addParams([':campaignId' => $campaign->id])
    ->andWhere(['type' => Campaign::TYPE_CHARITY_FUNDRAISER])
    ->andWhere(['tiedToParent' => 0])
    ->all();

$isTied = $campaign->tiedToParent && (sizeof($parentCampaigns) > 0);
$this->registerJsFile('@web/js/campaign/firstgiving.js', [
    'depends' => common\assets\CommonAsset::className(),
]);
$firstGiving = $campaign->getFirstGiving();
?>

<div id="collapseOne" class="panel-collapse collapse in <?= $isTied ? 'isTied' : '' ?>">
    <div class="module">
        <h5><i class="icon-list"></i>Campaign Details</h5>
        
        <!-- Campaign Name -->
        <div class="form-group">
            <?= $form->field($campaign, 'name', ['autoPlaceholder' => false])->label("Campaign Name"); ?>
        </div>

        <!-- Campaign Description -->
        <div class="form-group">
            <?= $form->field($campaign, 'description', ['autoPlaceholder' => false])->textarea(['maxlength' => Campaign::DESCRIPTION_MAX_LENGTH, 'rows' => 3]); ?>
        </div>

        <!-- Campaign Type -->
        <div class="form-group field-campaign-type">
            <label>Campaign Type</label>
            <?= Html::activeDropDownList($campaign, 'type', array_combine(Campaign::$TYPES, Campaign::$TYPES), ['class' => 'select-block']) ?>
        </div>

        <div id="notTiedCampaignContainer">
            <!-- Personal Fundraiser PayPal -->
            <div class="form-group pf-paypal">
                <div class="highlight-wrap">
                    <?= $form->field($campaign, 'paypalAddress', ['autoPlaceholder' => false])->label("PayPal Address"); ?>
                </div>
            </div>

            <!-- Donation Destination (Charity Dropdown / Custom Charity) -->
            <div id="donationDestination" data-donationDestination="<?= $campaign->donationDestination?>">
                <label> Charity Type <i class="icon icon-help" data-toggle="tooltip" data-placement="top" title="Raise money for your own cause. Or, search a list of popular charity organizations."></i>
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
                    <h5>Choose from one of our partnered organizations:</h5>
                    <?= Html::input('hidden', 'firstgiving', $firstGiving ? $firstGiving->organization_uuid : null, ['id' => 'firstgiving', 'class' => 'select-block']); ?>
                </div>
                <div class="customCharity highlight-wrap">
                    <div class="form-group">
                        <?= $form->field($campaign, 'customCharity', ['autoPlaceholder' => false])->label("Charity Name"); ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($campaign, 'customCharityPaypal', ['autoPlaceholder' => false])->label("Charity PayPal Address"); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Campaign Goal Amount -->
        <div class="field-campaign-goalamount form-group required">
            <label>Goal Amount</label>
            <?= $form->field($campaign, 'goalAmount', ['autoPlaceholder' => false])->label(false)->hiddenInput(); ?>
            <?= MaskedInput::widget([
                'name' => 'goal',
                'value' => $campaign->goalAmount ?: 0,
                'options' => [
                    'class' => 'form-control',
                    'id' => 'masked-input'
                ],
                'clientOptions' => [
                    'value' => $campaign->goalAmount,
                    'prefix' => '$',
                    'alias' =>  'decimal',
                    'groupSeparator' => ',',
                    'autoGroup' => true,
                    'autoUnmask' => true,
                    'rightAlign' => false,
                    'allowMinus' => false,
                    'allowPlus' => false
                ],
            ]) ?>
        </div>

        <!-- Campaign Dates/Times -->
        <div id="startEndContainer" class="cf">
            <?= $form->field($campaign, 'startDate')->label("Start Date/Time")->input('datetime-local'); ?>
            <?= $form->field($campaign, 'endDate')->label("End Date/Time")->input('datetime-local'); ?>
        </div>
    </div>
    <div class="module team">

        <h5><i class="icon-users"></i>Team Fundraising</h5>
		    <? if (\Yii::$app->user->identity->getPlan()==Plan::PLAN_PRO): ?>
		    <div class="form-group" id="teamQuestion">
		        <label>Enable Team Fundraising <i class="icon icon-help" data-toggle="tooltip" data-placement="top" title="Turn on to allow other Pullr users to join and contribute to this campaign."></i></label>
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
	        <label>Connect to Another Campaign <i class="icon icon-help" data-toggle="tooltip" data-placement="top" title="Turn on if you want this campaign to contribute to another campaign."></i></label>
	        <?= $form->field($campaign, 'tiedToParent')->label(false)->checkbox([], false); ?>
	        <div class="form-group field-campaign-parentcampaignid highlight-wrap">
	            <label>Fundraiser Campaign <i class="icon icon-help" data-toggle="tooltip" data-placement="top" title="Select the campaign to which you want to contribute."></i></label>
	            <?= Html::activeDropDownList($campaign, 'parentCampaignId', $keyValues, ['class' => 'select-block']) ?>
	        </div>
	    </div>
        <? endif; ?>
        
    </div>
</div>