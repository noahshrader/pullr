/*js at view/site/error.php page*/
// initialize big video
$(function() {
	var BV = new $.BigVideo();
    BV.init();
    BV.show('bigvideo/404.mp4',{ambient:true});
});