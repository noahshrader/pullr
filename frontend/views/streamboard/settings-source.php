<?
use yii\helpers\Url;

$sourceHref = Url::to('', true).'/source';
?>
<div id="source" class="tab-pane active">
    <iframe class="frame" src="<?=$sourceHref?>" scrolling="no"></iframe>
</div>