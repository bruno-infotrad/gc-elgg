<?php
 	global $CONFIG;
	
    function toggle_lang_init()
    {
		// Extend system CSS with our own styles for the language toggle button
		elgg_extend_view('css','toggle_language/css');
		
		// Add the language toggle button under the site name
        //extend_view('page_elements/header_contents','toggle_language/toggle_lang');
    }
 
    elgg_register_event_handler('init','system','toggle_lang_init');
	
	// Create a session variable if is not already set
/*
	if (!isset($_SESSION['language'])) { 
		$language = elgg_get_config('language');
		if ($language) {
			$_SESSION['language'] = $language;
		} else {
			$_SESSION['language'] = "en";
		}
	}
*/
	
	// Register actions
	elgg_register_action("toggle_language/toggle", $CONFIG->pluginspath."toggle_language/actions/toggle.php", 'public');
	
    ?>
