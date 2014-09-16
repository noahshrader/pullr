<?
use yii\helpers\Url;

$sourceHref = Url::to('', true) . '/source';
?>
<div class="text-center streamboard-settings-header">
    <button class="btn btn-secondary btn-sm" onclick="window.prompt('Copy to clipboard: CTRL+C, Enter', ' <?= $sourceHref ?>');">
        Copy Link
    </button>
</div>
<div>
	<iframe id="frame" src="<?= $sourceHref ?>"></iframe>
</div>
