<?
use yii\helpers\Url;

$sourceHref = Url::to('', true).'/source';
?>
<div class="tab-pane active" id="source">
    <div class="text-center">
        <button class="btn btn-primary" onclick="window.prompt('Copy to clipboard: CTRL+C, Enter', ' <?= $sourceHref ?>');">Copy Link</button>
    </div>
    <hr>
    <iframe src="<?=$sourceHref?>" >

    </iframe>
</div>