window.Pullr = window.Pullr || {};


/**
 * example: 
 * Pullr.Init({id:1, key: "test_key"}); 
 */
Pullr.Init = function (requestParams){
    Pullr.__ready = [];
    Pullr.requestParams = requestParams;
    $('#pullr').text('pullr-text here');
    Pullr.LoadTemplates();
    Pullr.LoadLayout();
    Pullr.UpdateEvent();
    Pullr.LoadChannels();
    Pullr.Ready(Pullr.Show);
    Pullr.Ready(Pullr.ShortCodes);
    Pullr.Run();
};

Pullr.Ready = function(func){
    Pullr.__ready.push(func);
}
Pullr.Run = function(){
    if (jQuery().loadTemplate && ($('#templateTeamMember').length>0) && Pullr.layout && Pullr.event && Pullr.channels ){
        console.log('loaded');
        while (Pullr.__ready.length > 0) 
        {
            var func = Pullr.__ready.shift();
            func();
        }
    } else {
        console.log('waiting response');
        setTimeout(function(){
            Pullr.Run();
        }, 20)
    }
}
Pullr.Show = function(){
    for (var key in Pullr.channels){
        /*making shallow copy*/
        var data = jQuery.extend({}, Pullr.channels[key]);
        data.image = data.image.size150;
        var $el = $('<div>').loadTemplate($('#templateTeamMember'), data);
        $el = $el.children();
        $el.addClass(data.status);
        $('#pullr-channels').append($el);
    }
}

Pullr.ShortCodes = function(){
    $('[data-pullr]').each(function(){
        var $el = $(this);
        /*
         * so we are having something like
         * [0] - event
         * [1] - name
         * [2] - maybe more variables in tree (unlimited)
         */
        var array = $el.data('pullr').split('-');
        
        var current = Pullr;
        while (array.length > 0){
            var name = array.shift();
            if (!current[name]){
                return;
            }
            
            current = current[name];
        }
        
        $el.text(current);
    })
}

/*that load jquery templates for showing data*/
Pullr.LoadTemplates = function(){
    $("body").append($("<div>").load(Pullr.TEMPLATES_URL));
    $.getScript(Pullr.JQUERY_TEMPLATES_URL);
}

Pullr.LoadLayout = function(){
    Pullr.Call('layout', {}, function (data) {
        Pullr.layout = data;
    });
}

Pullr.UpdateEvent = function(){
    Pullr.Call('event', {}, function (data) {
        Pullr.event = data;
    });
}

Pullr.LoadChannels = function(){
    Pullr.Call('channels', {}, function (data) {
        Pullr.channels = data;
    });
}

Pullr.MAIN_URL = "//localhost:8000/pullr/frontend/web/" 
Pullr.API_URL = Pullr.MAIN_URL + "api/";
Pullr.TEMPLATES_URL = Pullr.MAIN_URL + "public/jqt.html";
Pullr.JQUERY_TEMPLATES_URL = Pullr.MAIN_URL + "public/jquery.loadTemplate-1.4.3.min.js";
Pullr.Call = function (method, params, callback){
    if (!params){
        params = {};
    }
    
    for (var attrname in Pullr.requestParams) 
    { 
        params[attrname] = Pullr.requestParams[attrname]; 
    }
    var url = Pullr.API_URL + method;
    $.getJSON(url, params, callback);
}
