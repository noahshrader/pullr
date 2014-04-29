<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
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
        <div class='row'>
            <h1  data-pullr='event-name'></h1>
            <span data-pullr='event-startDateFormatted'></span> -
            <span data-pullr='event-endDateFormatted'></span>
            
        </div>
        
        <div id='pullr-player'>
            
        </div>
        <div id='pullr-channels'></div>
        <script type='text/javascript'>
            Pullr.Init({id: <?= $layout->id ?>, key: <?= json_encode($layout->key) ?>});
//            Pullr.Ready(function(){alert(Pullr.event.name)});
        </script>
    </body>
</html>
