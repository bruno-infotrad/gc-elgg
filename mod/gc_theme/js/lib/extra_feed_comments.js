/**
 *  JQuery more comments
 *   */

elgg.provide('elgg.extra_feed_comments');
elgg.extra_feed_comments.init = function(){
	$('[id^=extra-feed-comments-]').live('click', function (e){
		e.preventDefault();
		var guid = parseInt(this.id.match(/\d+$/));
		var icon='<div class="comment-icon-center">'+$(this).closest('.elgg-body.new-feed').find('li.elgg-menu-item-comment').html()+'</div>';
		var newicon = icon.replace('id="','id="dup-');
		var pos=$(this).closest('[class^=elgg-river-responses-]');
		elgg.fetch_extra_feed_comments(guid);
		$(pos).after(newicon);
	});
};
elgg.fetch_extra_feed_comments = function(guid){
        var data;
        //if (elgg.is_logged_in()) {
        	//$(document).ready(function() {
			var params = {'guid': guid};
			var more_comment_marker = '.elgg-river-participation-'+guid;
			$(more_comment_marker).replaceWith('<div class="elgg-ajax-loader"></div>');
			elgg.get('extra_feed_comments', {
				dataType: 'html', 
				data: params, 
				success: function(data) {
					data = data.replace('elgg-river-comments','elgg-river-comments-'+guid);
					var more_comment_marker = '.elgg-ajax-loader';
					var comment_marker = '.elgg-river-comments-'+guid;
					$(more_comment_marker).replaceWith('');
					$(comment_marker).replaceWith(data);
				}
			});
			return false;
		//});
	//};
};
elgg.register_hook_handler('init', 'system', elgg.extra_feed_comments.init);
