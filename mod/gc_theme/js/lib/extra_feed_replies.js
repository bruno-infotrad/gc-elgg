/**
 *  JQuery more group discussion replies
 *   */

elgg.provide('elgg.extra_feed_replies');

elgg.extra_feed_replies = function(guid){
        var data;
        if (elgg.is_logged_in()) {
        	$(document).ready(function() {
			var params = {'guid': guid};
			var more_comment_marker = '.elgg-river-participation-'+guid;
			$(more_comment_marker).replaceWith('<div class="elgg-ajax-loader"></div>');
			elgg.get('extra_feed_replies', {
				data: params, 
				dataType: 'html', 
				success: function(data) {
					var more_comment_marker = '.elgg-ajax-loader';
					var comment_marker = '.elgg-river-replies-'+guid;
					$(more_comment_marker).replaceWith('');
					$(comment_marker).replaceWith(data);
				}
			});
			return false;
		});
	};
};
