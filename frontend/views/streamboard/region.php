<?
use yii\web\View;
use frontend\models\streamboard\Streamboard;
$user = Streamboard::getCurrentUser();

?>

<div ng-app="regionApp" class="streamboardContainer">	
	<div class="regionsContainer regionsNumber1" ng-controller="RegionsCtrl">
	    <div class="region"
            <? if ( $showBackground): ?>
	    	    ng-style="{'background-color': region.backgroundColor}"
            <? else :?>
                ng-style="{'background-color': rgba(0, 255, 0, 0.02)}"                            
            <? endif; ?>
         	id='region-{{region.regionNumber}}' ng-click='testClick()'
            >
		<?= $this->render('region/single-region') ?>	
		</div>
	</div>
</div>

<?= $this->render('streamboard-js-variables', [
    'regionsData' => $regionsData,
    'donationsData' => $donationsData,
    'campaignsData' => $campaignsData,
    'streamboardConfig' => $streamboardConfig
]) ?>

<script type="text/javascript">
    <?
        $js = 'window.Pullr.Streamboard.regionNumber = '. $regionNumber . ';';
        $js .= 'window.Pullr.Streamboard.streamboardToken = "' . $streamboardToken . '";';
        $js .= 'window.Pullr.Streamboard.region = ' . json_encode($region) . ';';
        echo $js;
    ?>
</script>