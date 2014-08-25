<?
use common\components\streamboard\alert\AlertMediaManager;


$streamboard = [];
$streamboard['AlertMediaManager'] =  (new AlertMediaManager())->toArray();
?>
<script type="text/javascript">
    <?
        $js = 'window.Pullr.Streamboard = '.json_encode($streamboard).';';
        echo $js;
    ?>
</script>