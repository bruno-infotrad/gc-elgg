<?php
 
include dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."modules.php";
include dirname(__FILE__).DIRECTORY_SEPARATOR."lang/en.php";
if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."lang/".$lang.".php")) {
	include dirname(__FILE__).DIRECTORY_SEPARATOR."lang/".$lang.".php";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link rel="apple-touch-icon" href="images/apple-touch-icon.png"/> 
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta names="apple-mobile-web-app-status-bar-style" content="black-translucent" />
<meta name="apple-touch-fullscreen" content="yes" />
<title><?php echo $i_language[0];?></title>

<style type="text/css" media="screen">@import "css/cometchat.css";</style>
<script type="text/javascript" charset="utf-8" src="../js.php?silent=true&callbackfn=iphone"></script>
<script type="text/javascript" src="js/iscroll.js"></script>
<script type="text/javascript" src="js/iphone.php"></script>

</head>
<body>
<audio id="audio">
	<source src="mp3/beep.mp3" type="audio/mp3">
</audio>

<div id="spacer"></div>

<div id="whosonline">
	<div id="header" class="header">
		<div class="roundedleft">&nbsp;</div>
		<div class="roundedcenter"><?php echo $i_language[1];?></div>
		<div class="roundedright"><?php echo $i_language[2];?></div>
		<div style="clear:both"></div>
	</div>

	<div id="wrapper">
		<div id="scroller">
			<ul id="permanent">
			</ul>
			<ul id="wolist">
			</ul>
			<div id="endoftext"></div>
		</div>
	</div>
</div>

<div id="chatwindow">
</div>
</body>
</html>