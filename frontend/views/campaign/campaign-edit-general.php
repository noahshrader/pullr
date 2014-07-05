<?
use common\models\Campaign;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $form ActiveForm */


$parentCampaigns = \Yii::$app->user->identity->getParentCampaigns()->all();
$isTied = $campaign->tiedToParent && (sizeof($parentCampaigns) > 0);

?>
<div id="collapseOne" class="panel-collapse collapse in <?= $isTied ? 'isTied' : '' ?>">
        <?= $form->field($campaign, 'name', ['autoPlaceholder' => true]); ?>
        <?= $form->field($campaign, 'description', ['autoPlaceholder' => true])->textarea(['maxlength' => Campaign::DESCRIPTION_MAX_LENGTH, 'rows' => 6]); ?>
        <div class="form-group field-campaign-type">
            <label>Campaign Type:</label><i class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="left" title="Some tooltip here."></i>
            <?= Html::activeDropDownList($campaign, 'type', array_combine(Campaign::$TYPES, Campaign::$TYPES), ['class' => 'form-control select-block']) ?>
        </div>
        
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
                    <label>Fundraiser Campaign:</label>
                    <?= Html::activeDropDownList($campaign, 'parentCampaignId', $keyValues, ['class' => 'form-control select-block']) ?>
                </div>
            </div>
        <? endif; ?>
        <div id="notTiedCampaignContainer">
            <div id="startEndContainer">
                <? $tooltip = '<i class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="left" title="Start date tooltip"></i>'; ?>
                 <?= $form->field($campaign, 'startDate')->label("Start Date/Time: $tooltip")->input('datetime-local'); ?>
                <? $tooltip = '<i class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="left" title="End date tooltip"></i>'; ?>
                 <?= $form->field($campaign, 'endDate')->label("End Date/Time: $tooltip")->input('datetime-local'); ?>
            </div>

            <?= $form->field($campaign, 'goalAmount', ['autoPlaceholder' => true]); ?>

            <?= $form->field($campaign, 'paypalAddress', ['autoPlaceholder' => true]); ?>

            <div id="donationDestination" data-donationDestination="<?= $campaign->donationDestination?>">
                <label> Donation Destination 
                    <i class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="left" title="Select the donation destination."></i>
                </label>
                <div class="form-group field-campaign-donationDestination">
                    <?= Html::activeDropDownList($campaign, 'donationDestination', array_combine(Campaign::$DONATION_DESTINATIONS, Campaign::$DONATION_DESTINATIONS), ['class' => 'form-control select-block']) ?>
                    <?= Html::error($campaign, 'donationDestination', ['class' => 'help-block']) ?>
                </div>

                <div class='preapprovedCharity'>
                    <?= $form->field($campaign, 'charityId')->hiddenInput()->label(null, ['style' => 'display:none'])?>
                    <div class='charity-name <? if (!$campaign->charityId) { echo 'hidden';} ?>'>
                        <label>
                            Selected charity:
                        </label>
                        <span><?= $campaign->charity?$campaign->charity->name:''?></span>
                    </div>

                    <button class="btn btn-primary opentwo" type="button" onclick="campaignChooseCharity()">Choose a charity</button>

                    <br/>
                    <br/>
                </div>
                <div class='customCharity'>
                    <?= $form->field($campaign, 'customCharity', ['autoPlaceholder' => true]); ?>
                    <?= $form->field($campaign, 'customCharityPaypal', ['autoPlaceholder' => true]); ?>
                    <?= $form->field($campaign, 'customCharityDescription', ['autoPlaceholder' => true])->textarea(); ?>
                </div>

            </div>
        </div>
        <div class="clearfix"></div>
    </div>