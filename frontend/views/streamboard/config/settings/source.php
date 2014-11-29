<?
use yii\helpers\Url;
use yii\web\View;

/**@var $this View */
?>
<div ng-app="pullr.streamboard.sourceApp">    
    
    <? if ( ! $hideAngularJsPage ): ?>
    	<div ng-controller="SourceCtrl">
    		<?= $this->render('source-angular', $data); ?>
    	</div>
    <? else : ?>
    	<?= $this->render('source-php', $data); ?>
	<? endif; ?>    
</div>