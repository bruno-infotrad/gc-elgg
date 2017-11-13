<?php
/**
 * Elgg pageshell
 * The standard HTML page shell that everything else fits into
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['title'] The page title
 * @uses $vars['body'] The main content of the page
 * @uses $vars['sysmessages'] A 2d array of various message registers, passed from system_messages()
 */

// backward compatability support for plugins that are not using the new approach
// of routing through admin. See reportedcontent plugin for a simple example.
if (elgg_get_context() == 'admin') {
	elgg_deprecated_notice("admin plugins should route through 'admin'.", 1.8);
	elgg_admin_add_plugin_settings_menu();
	elgg_unregister_css('elgg');
	echo elgg_view('page/shells/admin', $vars);
	return true;
}

// Set the content type
header("Content-type: text/html; charset=UTF-8");

//<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
//<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
?>
<!DOCTYPE html>
<!--[if IE 7]>
<html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8]>
<html lang="en" class="no-js ie8"><![endif]-->
<!--[if gt IE 8]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<head>
<?php echo elgg_view('page/elements/head', $vars); ?>
</head>

<body style="background:#d3d3d3;">
<div id="wb-body">
	<div class="elgg-page elgg-page-default">
		<div class="elgg-page-messages">
			<?php echo elgg_view('page/elements/messages', array('object' => $vars['sysmessages'])); ?>
		</div>
	
				<?php echo elgg_view('page/elements/header', $vars); ?>
		<div class="elgg-page-body">
			<?php //<div class="elgg-inner"> ?>
			<div id="content">
				<?php echo elgg_view('page/elements/body', $vars); ?>
			</div>
		</div>
		<div class="elgg-page-footer">
			<?php echo elgg_view('page/elements/footer', $vars); ?>
		</div>
	</div>
</div>
<?php

echo elgg_view('page/elements/foot');
?>

<!-- SR [2012-10-04] Added for CometChat integration -->
<?php
// Moved up because of JE framework 
//For language preferences to kick in
if (elgg_is_logged_in()) {
	$site_url = elgg_get_site_url();
	//$commetchat_css_url = $site_url . 'cometchat/cometchatcss.php';
	//$commetchat_js_url  = $site_url . 'cometchat/cometchatjs.php';
}
?>
<script src="<?php echo $site_url;?>mod/gc_theme/views/default/gc_theme/dist/theme-gcwu-intranet/js/theme-min.js"></script>
<script src="<?php echo $site_url;?>mod/gc_theme/views/default/gc_theme/dist/js/pe-ap-min.js"></script>
