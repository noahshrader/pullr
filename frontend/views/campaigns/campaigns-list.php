<?

$this->registerJsFile('@web/js/lib/knockout-3.1.js');
$this->registerJsFile('@web/js/campaign/campaigns-filter.js',  [
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
        <?/*real attributes set in php code just few lines below, that is just mapping*/ ?>
        <a data-bind="attr: { href: href}, css: { active: isActive == true }" class='row campaign-item' >
            <div class="main-info">
                <h5 data-bind="text: type" class="layout-type"></h5>
                <h3 class="list-title" data-bind="text: name"></h3>
                <!-- ko if: tiedToParent -->
                <span class="mdi-social-group" data-toggle="tooltip" data-placement="right" title="Connected"></span>
                <!-- /ko -->
                <p class="list-total" data-bind="text: amountRaised"></p>
            </div>
        </a>
    </script> 
    <?php
    $campaignsArray = [];
    foreach ($campaigns as $campaign){
      $array = $campaign->toArray();
      $array['amountRaised'] = '$'.\common\components\PullrUtils::formatNumber($campaign->amountRaised, 2);
      $array['href'] = ($campaign->isNewRecord) ? 'app/campaigns/add' : "app/campaigns/view?id=".$campaign->id;
      $array['isActive'] = $currentCampaign && $campaign->id == $currentCampaign->id;
      $array['type'] = $campaign->type == 'Charity Fundraiser' ? 'Charity' : 'Personal';
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
</div>