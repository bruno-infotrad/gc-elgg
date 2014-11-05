<?php

if (!defined('CCADMIN')) { echo "NO DICE"; exit; }

if (empty($_GET['process'])) {
	global $getstylesheet;
	require dirname(__FILE__).'/config.php';

if ($displayStream == 1) {
	$displayStreamYes = 'checked="checked"';
	$displayStreamNo = '';
} else {
	$displayStreamNo = 'checked="checked"';
	$displayStreamYes = '';
}

echo <<<EOD
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

$getstylesheet
<form action="?module=dashboard&action=loadexternal&type=module&name=facebook&process=true" method="post">
<div id="content">
		<h2>Settings</h2>
		<h3>If you are unsure about any value, please skip them</h3>
		<div>
			<div id="centernav" style="width:380px">
				<div class="title">Facebook Fan Page ID:</div><div class="element"><input type="text" class="inputbox" name="pageId" value="$pageId"></div>
				<div style="clear:both;padding:5px;"></div>
				<div class="title">Facebook Fan Page Name:</div><div class="element"><input type="text" class="inputbox" name="pageName" value="$pageName"></div>
				<div style="clear:both;padding:5px;"></div>
					<div class="title">Display activity stream?</div><div class="element"><input name="displayStream" $displayStreamYes value="1"   type="radio">Yes <input name="displayStream" value="0" type="radio" $displayStreamNo>No</div>
				<div style="clear:both;padding:5px;"></div>
				<div class="title">Number of connections:</div><div class="element"><input type="text" class="inputbox" name="connections" value="$connections"></div>
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
	header("Location:?module=dashboard&action=loadexternal&type=module&name=facebook");
}