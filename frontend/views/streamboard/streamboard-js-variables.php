<?
use frontend\models\streamboard\WidgetCampaignBarAlerts;

$streamboard = [];
$streamboard['WidgetCampaignBarAlerts'] = [
        'PATH_TO_IMAGES' =>  WidgetCampaignBarAlerts::PATH_TO_IMAGES,
        'PATH_TO_SOUNDS' =>  WidgetCampaignBarAlerts::PATH_TO_SOUNDS,
        'PREDEFINED_IMAGES' => WidgetCampaignBarAlerts::PREDEFINED_IMAGES(),
        'PREDEFINED_SOUNDS' => WidgetCampaignBarAlerts::PREDEFINED_SOUNDS(),
    ];
?>
<script type="text/javascript">
    <?
        $js = 'window.Pullr.Streamboard = '.json_encode($streamboard).';';
        echo $js;
    ?>
</script>