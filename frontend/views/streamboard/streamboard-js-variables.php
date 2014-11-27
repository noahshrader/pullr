<?
use common\components\streamboard\alert\AlertMediaManager;


$streamboard = [];
$streamboard['AlertMediaManager'] =  (new AlertMediaManager())->toArray();
?>
<script type="text/javascript">
    <?
        $js = 'window.Pullr.Streamboard = '.json_encode($streamboard).';';
        $js .= 'window.Pullr.Streamboard.regionsData = ' . json_encode($regionsData) . ';';
        $js .= 'window.Pullr.Streamboard.donationsData = ' . json_encode($donationsData) . ';';
        $js .= 'window.Pullr.Streamboard.campaignsData = ' . json_encode($campaignsData) . ';';
        $js .= 'window.Pullr.Streamboard.streamboardConfig = ' . json_encode($streamboardConfig) . ';';
        echo $js;
    ?>
</script>