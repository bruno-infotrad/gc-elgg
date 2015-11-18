/**
 *  * Find is new feeds have benn posted, add number of new feed to feed menu item
 *   */

elgg.provide('elgg.new_feeds');



elgg.new_feeds.update = function(data, textStatus, XHR) {
		if (data.count > 0) {
			$('.elgg-new-feeds').html('('+elgg.echo(data.count)+')');
		}
		elgg.session.js_polling = data.js_polling_control;
}
elgg.new_feeds.init = function(){
	var data;
	if (elgg.is_logged_in()) {
		if (elgg.session.js_polling === "on") {
			elgg.action("action/dashboard/new_feeds",{
				error: function(json){ },
				success: function(json){ elgg.new_feeds.update(json) }
			});
		};
		setInterval(function(){
			var data;
			if (elgg.session.js_polling === "on") {
				elgg.action("action/dashboard/new_feeds",{
					error: function(json){ },
					success: function(json){ elgg.new_feeds.update(json) }
				});
			};
		},60000);
	};
};

elgg.register_hook_handler('init', 'system', elgg.new_feeds.init);



