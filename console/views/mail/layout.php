<? 
use common\components\Application;

?>

<div style="background-color: #111514; padding: 5px 0;">
    <div style="margin: 0 auto; width: 650px;">
        <a href="<?= Application::getBaseUrl() ?> "><h2 style="height: 30px; vertical-align: middle;">Pullr</h2></a>
    </div> 
</div>
<div style="background-color: #2c2d2f; padding: 15px 0;">
    <div style="margin: 0 auto; width: 660px;">
        <div style="font-size: 13px; background-color: #F9F5F1; padding: 10px; border-radius: 5px;overflow: hidden;"><?= $content ?></div>
        <div style="font-size: 12px; color: #666; padding-top: 10px;"><?= $footer ?></div>
    </div>
</div>