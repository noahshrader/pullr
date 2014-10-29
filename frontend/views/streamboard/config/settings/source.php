<?
use yii\helpers\Url;
use yii\web\View;

/**@var $this View */
?>
<div ng-app="pullr.streamboard.sourceApp">
    <div ng-controller="SourceCtrl">
        <?= $this->render('source-php', $data); ?>
        <?= $this->render('source-angular'); ?>
    </div>
</div>