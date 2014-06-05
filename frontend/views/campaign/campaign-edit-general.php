<?
use common\models\Campaign;
use yii\helpers\Html;
?>
    
    <div class="panel-collapse collapse in" id="collapseOne">
        <?= $form->field($campaign, 'name', ['autoPlaceholder' => true]); ?>
        <div class="form-group field-campaign-type">
            <label>Campaign Type:</label><i class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="left" title="Some tooltip here."></i>
            <?= Html::activeDropDownList($campaign, 'type', array_combine(Campaign::$TYPES, Campaign::$TYPES), ['class' => 'form-control select-block']) ?>
        </div>
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
        <?= $form->field($campaign, 'enableGoogleAnalytics')->label('Enable Google Analytics Tracking?')->checkbox([], false); ?>
        <div style="<?= ($campaign->enableGoogleAnalytics)?'' :'display:none' ?> " class="form-group field-campaign-googleAnalytics <?= ($campaign->hasErrors('googleAnalytics')) ? 'has-error' : '' ?>">
            <?= Html::activeInput('text', $campaign, 'googleAnalytics', ['class' => 'form-control', 'placeholder' => 'Google Analytics Tracking ID']) ?>
            <i class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="left" title="Add Google Analytics to your page by entering the Tracking ID you want to use with this layout. If you don't want to use this feauter, leave this blank."></i>
            <?= Html::error($campaign, 'googleAnalytics', ['class' => 'help-block']) ?>
        </div>
        
         <div class="form-group field-layout-streamService <?= ($campaign->hasErrors('streamService')) ? 'has-error' : '' ?>">
                        <?= Html::activeDropDownList($campaign, 'streamService', array_combine(Campaign::$STREAM_SERVICES, Campaign::$STREAM_SERVICES), ['class' => 'form-control select-block']) ?>
                        <i class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="left" title="Select the streaming service your want to pull from."></i>
                        <?= Html::error($campaign, 'streamService', ['class' => 'help-block']) ?>
        </div>
    
        <div class="clearfix"></div>
    </div>