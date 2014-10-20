<?
use yii\helpers\Url;

$sourceHref = Url::to('', true) . '/source';
?>
<div class="text-center streamboard-settings-header">

    <button class="btn btn-sm" id='btn-copy-source-link' 
    	data-clipboard-text='<?= $sourceHref ?>'>
        Copy Source URL
    	<p id='copied-message'>Copied link to clipboard</p>
    </button>
    
</div>
<div class="settings pane">
	<iframe id="frame" src="<?= $sourceHref ?>" scrolling="no"></iframe>
</div>