<?
use common\models\Campaign;

$this->registerJsFile('@web/js/lib/knockout-3.1.js');
$this->registerJsFile('@web/js/campaign/campaigns-filter.js', [
    'depends' => common\assets\CommonAsset::className(),
] );
?>

<div class="campaigns-list">
    <div class="list-search">
        <span>
            <input placeholder="Search…" type="search" data-bind="value: query, valueUpdate: 'keyup'" autocomplete="off" class="form-control">
        </span>
    </div>
    <div class="inner-list-wrap">
    <? if (sizeof($campaigns) > 0) { ?>
    <div data-bind="template: {name:'campaign', foreach:campaigns}"></div>
    <script type="text/html" id="campaign">
        <div data-bind="css: {active: isActive == true}" class="row campaign-item col-md-6">
            <div data-bind="attr: {class: type}">
                <h3 class="list-title" data-bind="text: name"></h3>
                <!-- ko if: tiedToParent -->
                    <span class="mdi-social-group" data-toggle="tooltip" data-placement="right" title="Connected"></span>
                <!-- /ko -->
                <p class="list-total" data-bind="text: amountRaised"></p>
                <a data-bind="attr: {href: href}">O</a>
                <a data-bind="attr: {href: edit}">E</a>
                <a data-bind="attr: {href: campaignpage}">CP</a>
                <a data-bind="attr: {href: donate}">D</a>
                <a data-bind="attr: {href: json}">J</a>
                <!-- ko if: pending -->
                    <a href="app/campaigns" data-bind="attr: {onclick: archive}">A</a>
                <!-- /ko -->
                <!-- ko if: active -->
                    <a href="app/campaigns" data-bind="attr: {onclick: restore}">R</a>
                <!-- /ko -->
                <!-- ko if: deleted -->
                    <a href="app/campaigns" data-bind="attr: {onclick: trash}">D</a>
                <!-- /ko -->
                <!-- ko if: goalamount -->
                    <div class="progress-wrap">
                        <div class="progress">
                            <div class="progress-line" data-bind="style: {width: progress}">
                                <span data-toggle="tooltip" data-placement="top"></span>
                            </div>
                        </div>
                    </div>
                <!-- /ko -->
            </div>
        </div>
    </script> 
    <?php
    $campaignsArray = [];
    foreach ($campaigns as $campaign){
      $array = $campaign->toArray();
      $array['amountRaised'] = '$'.\common\components\PullrUtils::formatNumber($campaign->amountRaised, 2);
      $array['edit'] = "javascript:$.pageslide({ direction: 'left', href: 'app/campaigns/edit?id=".$campaign->id."'})";
      $array['campaignpage'] = $campaign->user->getUrl().urlencode($campaign->alias);
      $array['donate'] = $campaign->user->getUrl().$campaign->alias."/donate";
      $array['json'] = $campaign->user->getUrl().$campaign->alias."/json";
      $array['archive'] = "return campaignChangeStatus('".$campaign->id.",".Campaign::STATUS_PENDING."')";
      $array['restore'] = "return campaignChangeStatus('".$campaign->id.",".Campaign::STATUS_ACTIVE."')";
      $array['trash'] = "return campaignChangeStatus('".$campaign->id.",".Campaign::STATUS_DELETED."')";
      $array['pending'] = $campaign->status != Campaign::STATUS_PENDING;
      $array['active'] = $campaign->status != Campaign::STATUS_ACTIVE;
      $array['deleted'] = $campaign->status != Campaign::STATUS_DELETED;
      $array['href'] = ($campaign->isNewRecord) ? 'app/campaigns/add' : "app/campaigns/view?id=".$campaign->id;
      $array['progress'] = "42%";
      $array['goalamount'] = $campaign->goalAmount > 0;
      $array['isActive'] = $currentCampaign && $campaign->id == $currentCampaign->id;
      $array['type'] = $campaign->type == 'Charity Fundraiser' ? 'charity' : 'personal';
      $array['tiedToParent'] = $campaign->tiedToParent;
      $campaignsArray[] = $array;
    }
    $js = 'campaignsWithFilter('.json_encode($campaignsArray).')';
    $this->registerJs($js);
    ?>
    <? } else { ?>
    <div class="text-center none">
        No campaigns here yet!
    </div>
    <? } ?>
    </div>
    <div id="pageslide">
    </div>
</div>