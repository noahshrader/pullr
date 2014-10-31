<?
use yii\helpers\Url;
use common\components\Application;
$sourceHref = Url::to(['streamboard/source'], true);
$publicSourceHref = Url::to(['streamboard/source','userId' => Application::getCurrentUser()->id], true);
?>
<div class="text-center streamboard-settings-header">

    <button class="btn btn-sm" id='btn-copy-source-link' 
    	data-clipboard-text='<?= $publicSourceHref?>'>
        Copy Source URL
 
    </button>
    <div id='copied-clipboard-tooltip' class="tooltip bottom fade in">
  		<div class="tooltip-arrow"></div>
  		<div class="tooltip-inner">Copied link to clipboard</div>
	</div>
</div>
<div class="settings pane">
	<iframe id="frame" src="<?= $sourceHref ?>" scrolling="no"></iframe>
</div>