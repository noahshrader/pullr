/*js at view/site/error.php page*/
// initialize big video
$(function() {
	var BV = new $.BigVideo({useFlashForFirefox:false});
    BV.init();
    BV.show('video/404.mp4',{altSource:'video/404.ogv'},{ambient:true});
});