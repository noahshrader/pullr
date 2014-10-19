$(window).load(function() {

	// Toggle Chat
	$('.togglechat').click(function () {
		$(this).toggleClass('chaton');
	  	$('.featuredchat').fadeToggle();
	  	$('.featuredstreamcontainer').toggleClass('biggerFeaturedStream');
	});

	// FitVid Init
	jQuery("#stream").fitVids();
});