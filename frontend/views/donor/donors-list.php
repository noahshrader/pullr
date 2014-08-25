<?

use common\assets\CommonAsset;

$this->registerJsFile('@web/js/lib/knockout-3.1.js');
$this->registerJsFile('@web/js/campaign/donors-filter.js',  CommonAsset::className() );
?>


<? if (sizeof($donors) > 0): ?>
<div class="list-search">
    <span>
        <input placeholder="Search…" type="search" data-bind="value: query, valueUpdate: 'keyup'" autocomplete="off" class="form-control">
    </span>
</div>
<div class="donors-list" >
        <div data-bind="template: {name:'donor', foreach:donors}">
        </div>
        <script type="text/html" id="donor">
            <?/*real attributes set in php code just few lines below, that is just mapping*/ ?>
            <a data-bind="attr: { href: href}, css: { active: isActive == true }" class='row campaign-item' >
                <div class="main-info">
                    <h3 data-bind="text: name"></h3>
                    <p data-bind="text: sum"></p>
                </div>
            </a>
        </script> 
        <?
        $js = 'donorsWithFilter('.json_encode($donors).')';
        $this->registerJs($js);
        ?>
</div>
<? else: ?>
<br>
<div class="text-center">
    No donors here yet.
</div>
<? endif; ?>
