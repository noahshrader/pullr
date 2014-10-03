window.Pullr = window.Pullr || {};

Pullr.MAIN_URL = "http://<?=$_SERVER['HTTP_HOST']?><?= \Yii::$app->urlManager->baseUrl?>/";
Pullr.API_URL = Pullr.MAIN_URL + "api/";
Pullr.ANGULAR_LIB_URL = 'http://code.angularjs.org/snapshot/angular.js';
Pullr.ANGULAR_APP_URL = Pullr.MAIN_URL + "public/api-widget.js";

Pullr.LAYOUT_TYPE_SINGLE = "<? echo common\models\Campaign::LAYOUT_TYPE_SINGLE; ?>";
Pullr.LAYOUT_TYPE_TEAM = "<? echo common\models\Campaign::LAYOUT_TYPE_TEAM; ?>";
Pullr.LAYOUT_TYPE_MULTI = "<? echo common\models\Campaign::LAYOUT_TYPE_MULTI; ?>";

/**
 * example:
 * Pullr.Init({id:1, key: "test_key"});
 */
Pullr.Init = function (requestParams){
    Pullr.requestParams = requestParams;
    Pullr.__ready = [];
    Pullr.LoadAngularLib();
};


Pullr.LoadAngularLib = function(){
    var script = document.createElement("script");
    script.type = 'text/javascript';
    script.src = Pullr.ANGULAR_LIB_URL;
    script.async = true;
    script.onload = function(){
        Pullr.LoadAngularDirective();
    }
    document.getElementsByTagName('head')[0].appendChild(script);
}

Pullr.LoadAngularDirective = function(){
    var script = document.createElement("script");
    script.type = 'text/javascript';
    script.src = Pullr.ANGULAR_APP_URL;
    document.getElementsByTagName('head')[0].appendChild(script);
}