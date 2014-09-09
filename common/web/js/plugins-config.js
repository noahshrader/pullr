$(function () {
	// Custom scrollbars
	$(".pane").mCustomScrollbar({
		theme:"minimal",
		mouseWheel:{
			preventDefault: true,
			scrollAmount: 10
		},
		scrollInertia: 80,
		callbacks:{
			alwaysTriggerOffsets: true,
			onTotalScrollBackOffset: 10,
			onScrollStart: function(){
				$('.top-menu').addClass('shadow');
			},
			onTotalScrollBack: function(){
				$('.top-menu').removeClass('shadow');
			}
		}
	});

	// enable tooltips
	$("[data-toggle='tooltip']").tooltip();
});