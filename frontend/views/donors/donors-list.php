<?php
$this->registerJsFile('@web/js/lib/knockout-3.1.js');
$this->registerJsFile('@web/js/campaign/donors-filter.js',  [
    'depends' => common\assets\CommonAsset::className(),
]);
?>

<div class="list-search">
    <span>
        <input placeholder="Search…" type="search" data-bind="value: query, valueUpdate: 'keyup'" autocomplete="off" class="form-control">
    </span>
</div>
<div class="donors-list inner-list-wrap">
    <? if (sizeof($donors) > 0) { ?>
    <div data-bind="template: {name:'donor', foreach:donors}"></div>
    <script type="text/html" id="donor">
        <?/*real attributes set in php code just few lines below, that is just mapping*/ ?>
        <a data-bind="attr: { href: href}, css: { active: isActive == true }" class='row donor-item' >
            <div class="main-info">
                <h3 class="list-title" data-bind="text: name"></h3>
                <p class="list-total" data-bind="text: sum"></p>
            </div>
        </a>
    </script> 
    <?
    $js = 'donorsWithFilter('.json_encode($donors).')';
    $this->registerJs($js);
    ?>
    <? } else { ?>
    <div class="text-center none">
        No donors here yet!
    </div>
    <? } ?>
</div>