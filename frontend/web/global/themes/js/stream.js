var myCustomAfterRender = function(){}
$(function(){

	myCustomAfterRender = function(){
		// Chat toggle
		$('.togglechat').click(function (e) {			
			$(this).toggleClass('chaton');
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
		// Stats background movement
		function CampaignPageBG() {
		scrollPos = $(this).scrollTop();
		$('.stats').css({
			'background-position' : '50% ' + (-scrollPos/8)+"px"
		});
		}
		$(window).scroll(function() {
			CampaignPageBG();
		});
		// Rolldown stats
		$(window).scroll(function() {
			if ($(this).scrollTop() >= 48) {
				$(".stats .project-progress").addClass('stuck');
			} else {
				$(".stats .project-progress").removeClass('stuck');
			}
			if ($(this).scrollTop() >= 360) {
				$(".slidestats").addClass('show');
			} else {
				$(".slidestats").removeClass('show');
			}
		});
	}	
});