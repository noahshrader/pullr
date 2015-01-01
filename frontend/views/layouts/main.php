<?php
use yii\helpers\Html;
use common\assets\FrontendAsset;
use common\assets\CommonAsset;
use yii\helpers\Url;
use frontend\models\streamboard\StreamboardConfig;
use common\models\Campaign;
/**
 * @var \yii\web\View $this
 * @var string $content
 */
CommonAsset::register($this);
FrontendAsset::register($this);

$streamboardConfig = StreamboardConfig::get();
if(isset($streamboardConfig))
{
    $js = 'Pullr.baseUrl = "'.Url::to('app').'";';
    $js .= 'Pullr.Streamboard = '.json_encode($streamboardConfig->toArray(['streamboardWidth', 'streamboardHeight', 'streamboardLeft', 'streamboardTop'])).';';
    $js .= 'Pullr.CAMPAIGN_TYPE_CHARITY_FUNDRAISER = "' . Campaign::TYPE_CHARITY_FUNDRAISER . '";';
    $this->registerJs($js);
}

$colorTheme = '';
$user = Yii::$app->user->identity;
if (isset($user))
{
    $user->setScenario('settings');
    $colorTheme = $user->colorTheme == ""?"light":$user->colorTheme;
}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <?= $this->render('baseHead') ?>
    <!-- BEGIN body -->
    <body class="<?php echo $colorTheme; ?>">
        <?php $this->beginBody() ?>

        <div class="primary-navigation"> <!-- BEGIN main navigation -->
            <?= $this->render('@common/views/leftmenu/avatar'); ?>
            <nav class="sidebar-nav nav-top">
                <ul>
                    <li>
                        <a class="dashboard <?if(Yii::$app->controller->id == 'site'):?>active<?endif;?>" title="Dashboard" href="app" data-toggle="tooltip" data-placement="bottom">
                            <i class="icon mdi-action-dashboard"></i>
                        </a>
                    </li>
                    <li>
                        <a class="campaign-link <?if(Yii::$app->controller->id == 'campaigns'):?>active<?endif;?>" title="Campaigns" href="app/campaigns" data-toggle="tooltip" data-placement="bottom">
                            <i class="icon mdi-av-equalizer"></i>
                        </a>
                    </li>
                    <li>
                        <a class="donor-link <?if(Yii::$app->controller->id == 'donors'):?>active<?endif;?>" title="Donors" href="app/donors" data-toggle="tooltip" data-placement="bottom">
                            <i class="icon mdi-social-person"></i>
                        </a>
                    </li>
                    <li>
                        <a class="settings <?if(Yii::$app->controller->id == 'settings'):?>active<?endif;?>" title="Settings" href="app/settings" data-toggle="tooltip" data-placement="bottom">
                            <i class="icon mdi-action-settings"></i>
                        </a>
                    </li>
                 </ul>
            </nav>
            <div class="branding">
                <a class="logo mdib-pullr-logo"></a>
            </div>
            <? if(\Yii::$app->user->identity->canCreateMoreCampaigns()): ?>
                <a href="app/campaigns/add" class="btn-add mdi-content-add" title="Add Campaign"></a>
            <? else:?>
                <a href="#" onclick="javascript:return false;" class="btn-add mdi-content-add" data-toggle="tooltip" data-placement="bottom" title="You have reached your active campaigns limit"></a>
            <? endif;?>
        </div> <!-- END main navigation -->

        <div class="main-wrapper large-menu-toggled">

            <?= $content ?>

            <?php $this->endBody() ?>

            <a class="streamboard" title="Streamboard" href="app/streamboard" target="_blank">
                <i class="icon mdi-av-web"></i>
            </a>
        </div> <!-- /main-wrapper -->
    </body>
</html>
<?php $this->endPage() ?>