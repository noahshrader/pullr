<?
use yii\helpers\Url;

$sourceHref = Url::to('', true) . '/source';
?>
<div class="text-center streamboard-settings-header">

    <button class="btn btn-sm" id='btn-copy-source-link' 
    	data-clipboard-text='<?= $sourceHref ?>'>
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