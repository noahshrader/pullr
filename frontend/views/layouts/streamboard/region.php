<?php
use yii\helpers\Html;
use common\assets\CommonAsset;
use common\assets\streamboard\StreamboardSingleRegionAsset;
use common\assets\streamboard\StreamboardCommonAsset;
use frontend\models\streamboard\Streamboard;
use common\models\Donation;
CommonAsset::register($this);
StreamboardCommonAsset::register($this);
StreamboardSingleRegionAsset::register($this);
$this->title = 'Streamboard';
$user = Streamboard::getCurrentUser();
$showBackground = isset($_GET['bg']) && $_GET['bg'] == 1 ? true : false ;
?>

<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
	    <meta charset="<?= Yii::$app->charset ?>"/>
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <title><?= Html::encode($this->title) ?></title>
	    <base href="<?= \Yii::$app->urlManager->createUrl('/'); ?>">
	    <link href="favicon.ico" rel="shortcut icon">
	    <?php $this->head() ?>
	    <script>
	        <?
	        $js = 'window.Pullr = window.Pullr || {}; ';
	        $js .= 'window.Pullr.twitchClientId = "'. \Yii::$app->params['twitchClientId'].'";';
	        $js .= 'window.Pullr.ANONYMOUS_NAME = "' . Donation::ANONYMOUS_NAME . '";';
	        $js .= 'Pullr.ENV = "'. YII_ENV.'";';
	        $publicParams = ['googleAPIKey'];
	        $params = array_intersect_key(Yii::$app->params, array_flip($publicParams));
	        $js .= 'Pullr.params = '.json_encode($params).';';
	        $onreadyJs = '';
	        if ($user) {	           
	           /**@var User $user*/
	           $js .= 'window.Pullr.user = ' . json_encode($user->toArray()) . ';';
	           $onreadyJs .= 'twitchEventsMonitor();';
	        }
	        echo $js;
	        $this->registerJs($onreadyJs);
	        ?>
	    </script>
	    <!-- Load Fonts -->
	    <link href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,700' rel='stylesheet' type='text/css'>
	    <? if ( ! $showBackground) : ?>
		    <style type="text/css">
			    ::-webkit-scrollbar { visibility: hidden; }
				body, html, .region, .streamboardContainer, .regionsContainer, .regionsContainer .region { 
					background-color: rgba(0, 255, 0, 0.02);				
				}

		    </style>
		<? endif; ?>
	</head>
    <body>
	<div class="spinner-wrap">
		<div class="sb-logo">
			<i class="mdib-pullr-logo"></i>
		</div>
		<div class="spinner">
			<div class="rect1"></div>
			<div class="rect2"></div>
			<div class="rect3"></div>
			<div class="rect4"></div>
			<div class="rect5"></div>
		</div>
	</div>
	<?php $this->beginBody() ?>
	<?= $content ?>
	<?php $this->endBody() ?>
	<script type="text/javascript">
		document.write('<script type="text/javascript" src="' + ('https:'==document.location.protocol?'https://':'http://c.') + 'jslogger.com/jslogger.js"><\/script>');
		</script>
		<script type="text/javascript">window.jslogger = new JSLogger();</script>
    </body>
</html>
<?php $this->endPage() ?>