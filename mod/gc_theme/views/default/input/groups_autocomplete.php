<?php 
	
	$name = elgg_extract("name", $vars); // input name of the selected group
	$id = elgg_extract("id", $vars);
	$relationship = elgg_extract("relationship", $vars);
	
	$destination = $id . "_autocomplete_results";
	
	$minChars = elgg_extract("minChars", $vars, 3);
	
	elgg_load_css("group_tools.autocomplete");
	
	$site_url = elgg_get_site_url();
?>
	<input type="text" name="name" id="<?php echo $id; ?>_autocomplete" class="elgg-input elgg-input-autocomplete" />
	
	<div id="<?php echo $destination; ?>"></div>
		
	<div class="clearfloat"></div>
	
	<script type="text/javascript">
		$(document).ready(function() {

			$("#<?php echo $id; ?>_autocomplete").each(function(){
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
						$.getJSON( "<?php echo $site_url; ?>groups_autocomplete", {
							q: request.term,
							'groups_guids': function() {
								var result = "";
								
								$("#<?php echo $destination; ?> input[name='<?php echo $name; ?>[]']").each(function(index, elem){
									if(result == ""){
										result = $(this).val();
									} else {
										result += "," + $(this).val();
									}
								});
			
								return result;
							}
							<?php 
							if(!empty($relationship)){ 
								echo ", 'relationship' : '" . $relationship . "'";
							}
							?>
						}, response );
					},
					search: function() {
						// custom minLength
						var term = this.value;
						if ( term.length < <?php echo $minChars; ?>){
							return false;
						}
					},
					focus: function() {
						// prevent value inserted on focus
						return false;
					},
					select: function( event, ui ) {
						// prevent value inserted on click
						return false;
					},
					autoFocus: true
				}).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
					var list_body = "";
					list_body = item.content;
					
				
					return $( "<li></li>" )
					.data( "item.autocomplete", item )
					.append( "<a>" + list_body + "</a>" )
					.appendTo( ul );
				};
			});

			$('#<?php echo $destination; ?> .elgg-icon-delete-alt').live("click", function(){
				$(this).parent('div').remove();
			});	
		});
		
	</script>
