var myCustomAfterRender = function(){}
$(function(){
	myCustomAfterRender = function(){
		$('.togglechat').click(function (e) {			
			$(this).toggleClass('chaton');
		  	$('.featuredchat').fadeToggle();
		  	$('.featuredstreamcontainer').toggleClass('biggerFeaturedStream');
		  	e.preventDefault();
		});
		$("#stream").fitVids();
	}
});