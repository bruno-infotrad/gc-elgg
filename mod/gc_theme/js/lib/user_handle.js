elgg.provide('elgg.user_handle');

elgg.user_handle = function(id){
        var data;
        if (elgg.is_logged_in()) {
		$(document).ready(function() {
        		var destination = id+'_autocomplete_results';
			$('[id$=textarea]').each(function(){
				$(this)
				// don't navigate away from the field on tab when selecting an item
				.bind( "keydown", function( event ) {
					if ( event.keyCode === $.ui.keyCode.TAB &&
							$( this ).data( "autocomplete" ).menu.active ) {
						event.preventDefault();
					}
				})
				.autocomplete({
					source: function( request, response ) {
						var usagers = request.term.match(/^@\w+|\s+@\w+/g);
						var usager = usagers[usagers.length-1].replace(/@/,'');
						var cible = elgg.get_site_url()+"user_autocomplete";
						jquery_string = "\"#"+destination+" input[name='<?php echo $name; ?>[]']\"";
						$.getJSON( cible, {
							q: usager,
							'user_guids': function() {
								var result = "";
								//$("#"+destination+" input[name='<?php echo $name; ?>[]']").each(function(index, elem){
								$(jquery_string).each(function(index, elem){
									if(result == ""){
										result = $(this).val();
									} else {
										result += "," + $(this).val();
									}
								});
			
								return result;
							}
						}, response );
					},
					search: function() {
						// minLength = 2
						var term = this.value.match(/^@\w{3,}|\s+@\w{3,}/g);
						if (term === null){
							return false;
						} else {
							var term2 = this.value.match(/^@\w+\W+|\s+@\w+\W+/g);
							//var term2 = this.value.match(/^@\w+\s+|\s+@\w+\s+/g);
							if (term2 !== null && term2.length == term.length){
								return false;
							} else {
								return true;
							}
						}
					},
					focus: function() {
						// prevent value inserted on focus
						return false;
					},
					select: function( event, ui ) {
						//Mean regexp for multiline support
						this.value = this.value.replace(/(.*)(\W?)@\w+$/g,"$1$2@"+ui.item.value);
						return false;
					},
					autoFocus: true
				}).data("ui-autocomplete")._renderItem = function( ul, item ) {
					var list_body = "";
					list_body = item.content;
					
				
					return $( "<li></li>" )
					.data( "item.autocomplete", item )
					.append( "<a>" + list_body + "</a>" )
					.appendTo( ul );
				};
			});
		});
        };
};
