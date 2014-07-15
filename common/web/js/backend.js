/*add class active to one item of main menu
 * that function is little bit different from frontend Pullr.setCurrentMenuActive
 */
Pullr.setCurrentMenuActiveBackend = function(){
    var regexp = new RegExp(Pullr.baseUrl+'/?([^/\?]*)', 'i');
    var matchResult = location.href.match(regexp);
    if (matchResult !== null){
        var match = matchResult[1];
    } else{
        return;
    }
    var selector = '.sidebar-nav.nav-top a[href="'+match+'"]';
    $(selector).addClass('active');
};