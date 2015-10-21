/**
 *  * Real time activity stream
 *   */

elgg.provide('elgg.activity_stream');

elgg.activity_stream.update = function(data, textStatus, XHR) {
			if (data.debut == 'true') {
				$('.elgg-ajax-loader').remove();
				$('.overview').append(elgg.echo(data.item[0]));
				$('.viewport').height('22em');
				var oScrollbar = $('.elgg-sidebar-alt-river-activity');
				if (oScrollbar.length > 0) {
				oScrollbar.tinyscrollbar();
				oScrollbar.tinyscrollbar_update({size: 'auto'});
				}
			} else if (data.item && data.item.length>0) {
				var i=0; var timer = setInterval(
				function() {
						var activity_item = data.item[i];
                                		$('#elgg-sidebar-alt-river').prepend(activity_item);
                				$('#elgg-sidebar-alt-river>.elgg-image-block.clearfix:last').remove();
                				$('#elgg-sidebar-alt-river div:first').slideUp( function () { $(this).prependTo($('#elgg-sidebar-alt-river')).slideDown(); });
						if(++i > data.item.length-1) clearInterval(timer);
				},4900)  
			}
}
elgg.activity_stream.init = function(){
        var data;
        if (elgg.is_logged_in()) {
		var oScrollbar = $('.elgg-sidebar-alt-river-activity');
		if (oScrollbar.length > 0) {
        		setTimeout(function(){
        		        var data;
        			elgg.action('action/dashboard/activity_stream', {
						 data: {
                		                                debut: true,
								page_owner_guid: elgg.get_page_owner_guid()
                		                        },
                			                //error: function(json){ },
                		                        success: function(json){
                		                                elgg.activity_stream.update(json);
                		                        }
				});
        		},5000);
        		setInterval(function(){
        		        var data;
				if (elgg.session.js_polling === "on") {
        				elgg.action('action/dashboard/activity_stream', {
							 data: {
                			                                debut: false,
									page_owner_guid: elgg.get_page_owner_guid()
                			                        },
                			                	//error: function(json){ },
                			                        success: function(json){
                			                                elgg.activity_stream.update(json);
                			                        }
					});
        			}
        		},60000);
        	};
        };
};

elgg.register_hook_handler('init', 'system', elgg.activity_stream.init);
