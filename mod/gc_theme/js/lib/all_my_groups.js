/**
 *  JQuery more comments
 *   */

elgg.provide('elgg.all_my_groups');

elgg.all_my_groups = function(){
        var data;
        if (elgg.is_logged_in()) {
	$(document).ready(function() {
        	$('#all-my-groups').live('click',function() {
			var user = elgg.get_logged_in_user_entity();
			var params = {'username': user.username};
			var more_groups_marker = 'li.elgg-menu-item-groups-yours-more';
			$(more_groups_marker).replaceWith('<div class="elgg-ajax-loader allmygroups"></div>');
			elgg.get('all_my_groups', {
				data: params, 
				dataType: 'html', 
				success: function(data) {
					var more_comment_marker = '.elgg-ajax-loader.allmygroups';
					$(more_comment_marker).replaceWith(data);
				}
			});
			return false;
		});
	});
	};
};
elgg.register_hook_handler('init', 'system', elgg.all_my_groups);
