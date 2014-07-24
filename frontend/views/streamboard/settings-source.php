<?
use yii\helpers\Url;

$sourceHref = Url::to('', true).'/source';
?>
<div id="source" class="tab-pane active">
    <iframe id="sourcecode" class="frame" src="<?=$sourceHref?>"></iframe>
</div>