<?php 
	/**
	* Import Users.
	* 
	* Settings and Action screen.
	* 
	* @package import_users
	* @author Sebastien Routier
	* @copyright Sebastien Routier 2013
	* @license http://opensource.org/licenses/GPL-3.0 (GPL-3.0)
	*/



$GLOBALS['IMPUSR_PID'] = getmypid();
// $GLOBALS['IMPUSR_LOG'] = new FlexLog(FlexLogLevel::DEBUG);
$GLOBALS['IMPUSR_LOG'] = new FlexLog(FlexLogLevel::ERROR);
$GLOBALS['IMPUSR_LOG']->debug("IMPUSR: FlexLog initialized." . " PID: " . $GLOBALS['IMPUSR_PID']);



// Add admin menu item
// @todo Might want to move this to a 'feedback' section. something other than utils
elgg_register_admin_menu_item('administer', 'import_users', 'administer_utilities');


// Register actions
$action_path = elgg_get_plugins_path() . "import_users/actions/import_users";
elgg_register_action('import_users/import_users_csv', "$action_path/import_users_csv.php");
	
// file path to the page scripts
$lib_path = elgg_get_plugins_path() . 'import_users/lib';
require("$lib_path/CSVFile.php");
require("$lib_path/functions.php");
	
