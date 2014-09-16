<?
use yii\helpers\Url;

$sourceHref = Url::to('', true) . '/source';
?>
<div class="text-center streamboard-settings-header">
    <button class="btn btn-secondary btn-sm" onclick="window.prompt('Copy to clipboard: CTRL+C, Enter', ' <?= $sourceHref ?>');">
        Copy Link
    </button>
</div>
<script type="text/javascript">
	function iframeLoaded() {
	  var iFrameID = document.getElementById('idIframe');
	  if(iFrameID) {
	        // here you can make the height, I delete it first, then I make it again
	        iFrameID.height = "";
	        iFrameID.height = iFrameID.contentWindow.document.body.scrollHeight + "px";
	  }   
	}
</script>
<div class="view-source pane">
	<iframe id="frame" src="<?= $sourceHref ?>" onload="iframeLoaded()" scrolling="no"></iframe>
</div>