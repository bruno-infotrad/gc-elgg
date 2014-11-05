<?php

    /**
	 * Toggle language action
	 */
	
	// Register actions
	//register_action("togglelang");
	
    // Toggle language 
	
        $user = elgg_get_logged_in_user_entity();

	/**
        if ($user) {
		if ($user->language == 'en' || $user->language == '') {
                	//$user->language = 'fr';
			$_SESSION['language'] = 'fr';
		} else {
                	//$user->language = 'en';
                	$_SESSION['language'] = 'en';
		}
		$user->save();
        } else { */
		$language = $_SESSION['language'];
        	if ($language == 'en') {
	                $_SESSION['language'] = 'fr';
        	} else {
                	$_SESSION['language'] = 'en';
        	}
	//}
	//register_translations(dirname(dirname(dirname(__FILE__))) . "/languages/");
	forward($_SERVER['HTTP_REFERER']);

?>
