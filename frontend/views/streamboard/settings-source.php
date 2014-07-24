<?
use yii\helpers\Url;

$sourceHref = Url::to('', true).'/source';
?>
<div id="source" class="tab-pane active">
    <div id="streamboard-settings-header" class="text-center">
        <button class="btn btn-primary" onclick="window.prompt('Copy to clipboard: CTRL+C, Enter', ' <?= $sourceHref ?>');">Copy Link</button>
    </div>

    <iframe class="frame" src="<?=$sourceHref?>" ></iframe>
    
</div>