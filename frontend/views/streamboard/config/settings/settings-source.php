<?
use yii\helpers\Url;

$sourceHref = Url::to('', true) . '/source';
?>
<div class="text-center streamboard-settings-header">
    <button class="btn btn-sm" onclick="window.prompt('Copy to clipboard: CTRL+C, Enter', ' <?= $sourceHref ?>');">
        Copy Source URL
    </button>
</div>
<div class="settings pane">
	<iframe id="frame" src="<?= $sourceHref ?>" scrolling="no"></iframe>
</div>