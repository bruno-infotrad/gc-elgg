/**
 *  * Find is new feeds have benn posted, add number of new feed to feed menu item
 *   */

elgg.provide('elgg.new_messages');



elgg.new_messages.update = function(data, textStatus, XHR) {
		if (data.message_count > 0) {
			$('.gc-messages-new').html(elgg.echo(data.message_count));
			$('.gc-messages-new').css("display","inline");
		}
}
elgg.new_messages.init = function(){
	var data;
	if (elgg.is_logged_in()) {
		if (elgg.session.js_polling === "on") {
			elgg.action("action/dashboard/new_messages",elgg.new_messages.update);
		}
		setInterval(function(){
			var data;
			if (elgg.session.js_polling === "on") {
				elgg.action("action/dashboard/new_messages",elgg.new_messages.update);
			};
		},60000);
	};
};

elgg.register_hook_handler('init', 'system', elgg.new_messages.init);



