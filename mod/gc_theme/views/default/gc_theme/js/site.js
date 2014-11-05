(function ($) {
	function handleMenuToggleClicked () {
		var $parent = $(this).parent();

		$parent.toggleClass('open');
	}

	function setupExpandableMenus() {
		$('#content').delegate('.button-expandable', 'click', handleMenuToggleClicked);
	}

	$(document).ready(function () {
		setupExpandableMenus();
	});
})(jQuery);