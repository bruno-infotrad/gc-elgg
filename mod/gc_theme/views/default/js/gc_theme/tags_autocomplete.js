//<script>

/**
 * Autocomplete @mentions
 *
 * Fetch and display a list of matching users when writing a @mention and
 * autocomplete the selected user.
 */
define(function(require) {
	var $ = require('jquery');
	var elgg = require('elgg');

	var handleResponse = function (json) {
		var existing_tags = '';
		$(json).each(function(index, tag) {
			existing_tags += '<li id="'+tag.content+'">' + tag.content + "</li>";
		});
		$('#elgg-input-group-tags-autocomplete-results').html(existing_tags);
		$('#elgg-input-group-tags-autocomplete-results li').bind('click', function (e) {
			replacement = $(this).attr('id');
			$('#elgg-input-group-tags-autocomplete').val(function(index, value){return value.replace(search_string,replacement);});
		});
	};

	var autocomplete = function (content,search_string) {
		var options = {success: handleResponse};
		elgg.get(elgg.config.wwwroot + 'group_tags_autocomplete?q='+search_string, options);
	};

	var init = function() {
		$(document).click(function(e) {   
			if(e.target.id != 'elgg-input-group-tags-autocomplete-results') {
				$("#elgg-input-group-tags-autocomplete-results").hide();   
			} 
		});
		$('#elgg-input-group-tags-autocomplete').bind('keyup', function(e) {
			$("#elgg-input-group-tags-autocomplete-results").html('');   
			$("#elgg-input-group-tags-autocomplete-results").show();   

			// Hide on backspace, enter and escape
			if (e.which == 8 || e.which == 13|| e.which == 27) {
				$("#elgg-input-group-tags-autocomplete-results").hide();   
			} else {
				content = $(this).val();
				parts = content.split(',');
				//console.log('PARTS='+parts);
				if (parts.length === 0) {
					search_string = content;
				} else {
					search_string = parts[parts.length - 1];
				}
				//console.log('CONTENT='+search_string);
				autocomplete(content,search_string);
			}
		});

	};

	elgg.register_hook_handler('init', 'system', init, 9999);

	return {
		autocomplete: autocomplete
	};
});
