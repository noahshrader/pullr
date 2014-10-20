<?
use yii\helpers\Url;

$sourceHref = Url::to('', true) . '/source';
?>
<div class="text-center streamboard-settings-header">
<<<<<<< HEAD
    <button class="btn btn-sm" id='btn-copy-source-link' 
    	data-clipboard-text='<?= $sourceHref ?>'>        
    </button>
    <p id='copied-message'>Copied link to clipboard</p>
</div>
<div class="settings pane">
	<iframe id="frame" src="<?= $sourceHref ?>" scrolling="no"></iframe>
</div>