window.Pullr = window.Pullr || {};

Pullr.MAIN_URL = "//<?=$_SERVER['HTTP_HOST']?><?= \Yii::$app->urlManager->baseUrl?>/";
Pullr.API_URL = Pullr.MAIN_URL + "api/";

Pullr.TEMPLATES_URL = Pullr.MAIN_URL + "public/jqt.html";
Pullr.JQUERY_TEMPLATES_URL = Pullr.MAIN_URL + "public/jquery.loadTemplate-1.4.3.min.js";

Pullr.ANGULAR_LIB_URL = 'public/angular-1.3.4.min.js';
Pullr.ANGULAR_APP_URL = Pullr.MAIN_URL + "public/api-widget.js";

Pullr.MAGNIFIC_POPUP_JS = Pullr.MAIN_URL + "js/lib/magnificpopup.js";
Pullr.MAGNIFIC_POPUP_CSS = Pullr.MAIN_URL + "js/lib/magnificpopup.css";
Pullr.POPUP_CSS = Pullr.MAIN_URL + 'global/themes/css/popup.css';

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
    Pullr.LoadMagnificPopup();
    Pullr.Run();
};

Pullr.LoadMagnificPopup = function() {
    var script = document.createElement("script");
    script.type = 'text/javascript';
    script.src = Pullr.MAGNIFIC_POPUP_JS;
    document.getElementsByTagName('head')[0].appendChild(script);
    Pullr.appendCssFile('magnificpopupCss', Pullr.MAGNIFIC_POPUP_CSS);
    Pullr.appendCssFile('popupCss', Pullr.POPUP_CSS);
}


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

Pullr.Ready = function(func){
    Pullr.__ready.push(func);
}

Pullr.Run = function(){
    if (jQuery().loadTemplate && ($('#templateTeamMember').length>0) && Pullr.campaign && Pullr.channels ){
        console.log('loaded');
        while (Pullr.__ready.length > 0) 
        {
            var func = Pullr.__ready.shift();
            func();
        }
    } else {
      
        setTimeout(function(){
            Pullr.Run();
        }, 20)
    }
}

function resizeIframe(obj) {
    obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
}

Pullr.appendCssFile = function(id, url) {
    var $ = document; 
    var cssId = id; 
    if (!$.getElementById(cssId))
    {
        var head  = $.getElementsByTagName('head')[0];
        var link  = $.createElement('link');
        link.id   = cssId;
        link.rel  = 'stylesheet';
        link.type = 'text/css';
        link.href = url;
        link.media = 'all';
        head.appendChild(link);
    }
}