elgg.provide('elgg.contributed_by');

elgg.contributed_by = function(id){
        var data;
        if (elgg.is_logged_in()) {
		$(document).ready(function() {
        		var destination = id+'_autocomplete_results';
			$('#search-contrib-by').each(function(){
				$(this)
				// don't navigate away from the field on tab when selecting an item
				.bind( "keydown", function( event ) {
					if ( event.keyCode === $.ui.keyCode.TAB &&
							$( this ).data( "ui-autocomplete" ).menu.active ) {
						event.preventDefault();
					}
				})
				.autocomplete({
					source: function( request, response ) {
						var cible = elgg.get_site_url()+"user_autocomplete";
						$.getJSON( cible, {
							q: request.term,
							'user_guids': function() {
								var result = "";
								$('#'+destination).each(function(index, elem){
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
						// minLength = 3
						var term = this.value;
                                                if ( term.length < 3){
                                                        return false;
                                                }
					},
					focus: function() {
						// prevent value inserted on focus
						return false;
					},
					select: function( event, ui ) {
						//Mean regexp for multiline support
						this.value = ui.item.value;
						return false;
					},
					autoFocus: true
				}).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
					var list_body = "";
					list_body = item.content;
					return $( "<li></li>" )
					.data( "ui-autocomplete-item", item )
					.append( "<a>" + list_body + "</a>" )
					.appendTo( ul );
				};
			});
		});
        };
};
