$(function () {
	// Custom scrollbars
	$("#sidebar, .campaigns-list-wrap, .site-content, .donations-list, #settingsTab .tab-pane").mCustomScrollbar({
		theme:"minimal",
		mouseWheel:{
			preventDefault: true,
			scrollAmount: 10
		},
		scrollInertia: 40
	});

	// enable tooltips
	$("[data-toggle='tooltip']").tooltip();

	// enable bootstrap selects
	$('select').select2({
		minimumResultsForSearch: -1
	});
});