<?
use yii\helpers\Url;
use yii\web\View;

/**@var $this View */
?>
<div ng-app="pullr.streamboard.sourceApp">    
    <?= $this->render('source-php', $data); ?>
    <? if ( ! $hideAngularJsPage ): ?>
    	<div ng-controller="SourceCtrl">
    		<?= $this->render('source-angular', $data); ?>
    	</div>
	<? endif; ?>    
</div>