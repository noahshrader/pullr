$(function () {
	// toggle chat
	$(".togglechat").click(function(){
		$(".embed-chat").toggleClass("active");
		$(".feed-video .embed").toggleClass("active");
   	});

	// team carousel list
   	$(".team-mates").owlCarousel({
		items : 8, 
		itemsDesktop : [1000,5], 
		itemsDesktopSmall : [900,6], 
		itemsTablet: [600,4], 
		itemsMobile : false,
		navigation: true,
		navigationText: [
			"<i class='icon-chevron-left icon-white'></i>",
			"<i class='icon-chevron-right icon-white'></i>"
			]
   		});
   			 
   	$('.avatar-link').on('click', function(event){
		var $this = $(this);
		if($this.hasClass('clicked')){
		$this.removeAttr('style').removeClass('clicked');
		} else {
			$this.css('background','#7fc242').addClass('clicked');
		}
  	});
});