<?
use yii\helpers\Url;

$sourceHref = Url::to('', true) . '/source';
?>
<div class="text-center streamboard-settings-header">
    <button class="btn btn-secondary btn-sm" id='btn-copy-source-link' 
    	data-clipboard-text='<?= $sourceHref ?>'>
        Copy Link
    </button>
    <p id='copied-message'>Copied link to clipboard</p>
</div>
<div class="view-source pane">
	<iframe id="frame" src="<?= $sourceHref ?>" scrolling="no"></iframe>
</div>