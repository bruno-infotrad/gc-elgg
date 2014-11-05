<?php

if (!defined('CCADMIN')) { echo "NO DICE"; exit; }

if (empty($_GET['process'])) {
	global $getstylesheet;
	require dirname(__FILE__).'/config.php';

echo <<<EOD
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

$getstylesheet
<form action="?module=dashboard&action=loadexternal&type=module&name=twitter&process=true" method="post">
<div id="content">
		<h2>Settings</h2>
		<h3>If you are unsure about any value, please skip them</h3>
		<div>
			<div id="centernav" style="width:380px">
				<div class="title">Twitter Username:</div><div class="element"><input type="text" class="inputbox" name="nusername" value="$nusername"></div>
				<div style="clear:both;padding:5px;"></div>
			</div>
		</div>

		<div style="clear:both;padding:7.5px;"></div>
		<input type="submit" value="Update Settings" class="button">&nbsp;&nbsp;or <a href="javascript:window.close();">cancel or close</a>
		<input type="hidden" value="{$_SESSION['token']}" name="token">
</div>
</form>
EOD;
} else {
	$data = '';
	foreach ($_POST as $field => $value) {
		$data .= '$'.$field.' = \''.$value.'\';'."\r\n";
	}
	configeditor('SETTINGS',$data,0,dirname(__FILE__).'/config.php');	
	header("Location:?module=dashboard&action=loadexternal&type=module&name=twitter");
}