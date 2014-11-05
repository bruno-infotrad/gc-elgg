/**
 *  JQuery more comments
 *   */

elgg.provide('elgg.extra_feed_comments');

elgg.extra_feed_comments = function(guid){
        var data;
        if (elgg.is_logged_in()) {
        	$(document).ready(function() {
			var params = {'guid': guid};
			var more_comment_marker = '.elgg-river-participation-'+guid;
			$(more_comment_marker).replaceWith('<div class="elgg-ajax-loader"></div>');
			elgg.get('extra_feed_comments', {
				data: params, 
				dataType: 'html', 
				success: function(data) {
					var more_comment_marker = '.elgg-ajax-loader';
					var comment_marker = '.elgg-river-comments-'+guid;
					$(more_comment_marker).replaceWith('');
					$(comment_marker).replaceWith(data);
				}
			});
			return false;
		});
	};
};
