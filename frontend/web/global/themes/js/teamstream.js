var myCustomAfterRender = function(){}
$(function(){
	myCustomAfterRender = function(){
		var innerWidth = $('.team-wrap').innerWidth();
		$('.team-wrap').width(innerWidth);
	}
});