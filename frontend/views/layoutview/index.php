<!DOCTYPE html>
<html>
    <head>
        <title>Pullr Api Test</title>
        <meta charset="UTF-8">
        <base href="<?= \Yii::$app->urlManager->createUrl('/'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> 
        <script src="//ajax.aspnetcdn.com/ajax/jquery.templates/beta1/jquery.tmpl.min.js"></script> 
        <script src="api/js"></script>
        <link  rel="stylesheet" href="layoutview/bootstrap.css" />
        <link  rel="stylesheet" href="layoutview/api.css" />
    </head>
    <body>
        <button href="<?= yii\helpers\Url::to().'/donate' ?>" class="btn btn-primary donate" data-effect="mfp-zoom-in">Donate</button>
        <div class='row'>
            <h1  data-pullr='campaign-name'></h1><h3>for <span data-pullr="campaign-charity-name"></span></h3>
            
            <span data-pullr='campaign-startDateFormatted'></span> -
            <span data-pullr='campaign-endDateFormatted'></span>
            
        </div>
        
        <div id='pullr-player'>
            
        </div>
        <div id='pullr-channels'></div>
        <script type='text/javascript'>
            Pullr.Init({id: <?= $campaign->id ?>, key: <?= json_encode($campaign->key) ?>});
            // Pullr.Ready(function(){alert(Pullr.event.name)});
        </script>
    </body>
</html>
