<?
use yii\helpers\Url;

$sourceHref = Url::to('', true) . '/source';
?>
<div class="text-center streamboard-settings-header">
    <button class="btn btn-primary" onclick="window.prompt('Copy to clipboard: CTRL+C, Enter', ' <?= $sourceHref ?>');">
        Copy Link
    </button>
</div>
<div class="pane">
	<iframe class="frame" src="<?= $sourceHref ?>" scrolling="no"></iframe>
</div>
