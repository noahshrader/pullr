var myCustomAfterRender = function(){}
$(function(){

	myCustomAfterRender = function(){
		// Chat toggle
		$('.togglechat').click(function (e) {			
			$(this).toggleClass('chatoff');
		  	$('.featuredchat').fadeToggle();
		  	$('.featuredstreamcontainer').toggleClass('biggerFeaturedStream');
		  	e.preventDefault();
		});
		// Flexible videos
		$("#stream").fitVids();
		// Donation form
		var meny = Meny.create({
			menuElement: document.querySelector( '.donation-form' ),
			contentsElement: document.querySelector( '.wrapper' ),
			position: Meny.getQuery().p || 'right',
			angle: 0,
			width: 580,
			overlap: 0,
			transitionDuration: '0.5s',
			mouse: false,
			gradient: 'rgba(9,10,12,.6) 100%, rgba(9,10,12,.6) 100%)',
			touch: true
		});
		$('.donate').click(function(e) {
			meny.open();
			e.stopPropagation();
		});
		$(".wrapper").on( "click", function(e) {
			meny.close();
		});
		$(document).on( "eventhandler", function() {
			meny.close();
		});
		// Rolldown stats
		$(window).scroll(function() {
			if ($(this).scrollTop() >= 420) {
				$(".slidestats").addClass('show');
			} else {
				$(".slidestats").removeClass('show');
			}
		});
	}	
});