$(function () {
	// Custom scrollbars
	$(".pane").mCustomScrollbar({
		theme:"minimal",
		mouseWheel:{
			preventDefault: true,
			scrollAmount: 10
		},
		scrollInertia: 40
	});

	// enable tooltips
	$("[data-toggle='tooltip']").tooltip();
});