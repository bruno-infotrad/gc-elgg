<?php

/*

CometChat
Copyright (c) 2012 Inscripts

CometChat ('the Software') is a copyrighted work of authorship. Inscripts 
retains ownership of the Software and any copies of it, regardless of the 
form in which the copies may exist. This license is not a sale of the 
original Software or any copies.

By installing and using CometChat on your server, you agree to the following
terms and conditions. Such agreement is either on your own behalf or on behalf
of any corporate entity which employs you or which you represent
('Corporate Licensee'). In this Agreement, 'you' includes both the reader
and any Corporate Licensee and 'Inscripts' means Inscripts (I) Private Limited:

CometChat license grants you the right to run one instance (a single installation)
of the Software on one web server and one web site for each license purchased.
Each license may power one instance of the Software on one domain. For each 
installed instance of the Software, a separate license is required. 
The Software is licensed only to you. You may not rent, lease, sublicense, sell,
assign, pledge, transfer or otherwise dispose of the Software in any form, on
a temporary or permanent basis, without the prior written consent of Inscripts. 

The license is effective until terminated. You may terminate it
at any time by uninstalling the Software and destroying any copies in any form. 

The Software source code may be altered (at your risk) 

All Software copyright notices within the scripts must remain unchanged (and visible). 

The Software may not be used for anything that would represent or is associated
with an Intellectual Property violation, including, but not limited to, 
engaging in any activity that infringes or misappropriates the intellectual property
rights of others, including copyrights, trademarks, service marks, trade secrets, 
software piracy, and patents held by individuals, corporations, or other entities. 

If any of the terms of this Agreement are violated, Inscripts reserves the right 
to revoke the Software license at any time. 

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

*/

if (!defined('CCADMIN')) { echo "NO DICE"; exit; }

$navigation = <<<EOD
	<div id="leftnav">
		<a href="?module=settings">Settings</a>
EOD;

if (defined('SWITCH_ENABLED') && SWITCH_ENABLED == 1) {
	$navigation .= <<<EOD
		<a href="?module=settings&action=whosonline">Whos Online List</a>
EOD;
}

$navigation .= <<<EOD
		<a href="?module=settings&action=comet">Comet Service</a>
		<a href="?module=settings&action=guests">Guest Chat</a>
		<a href="?module=settings&action=banuser">Banned words &amp; users</a>
		<a href="?module=settings&action=baseurl">Change Base URL</a>
		<a href="?module=settings&action=changeuserpass">Change Admin User/Pass</a>
		<a href="?module=settings&action=disablecometchat">Disable CometChat</a>
	</div>
EOD;

$options = array(
	"lightWeight"	 			  => array('choice','Switch on light-weight chat?'),
	 "hideOffline"	 			  => array('choice','Hide offline users in Whos Online list?'),
	 "autoPopupChatbox"	 	 	  => array('choice','Auto-open chatbox when a new message arrives'),
	 "messageBeep"	 	 	 	  => array('choice','Beep on arrival of message from new user?'),
	 "beepOnAllMessages"	 	  => array('choice','Beep on arrival of all messages?'),
	 "barType"	 	 	 	 	  => array('dropdown','Bar layout',array ('fixed','fluid')),
	 "barWidth"	 	 	 	 	  => array('textbox','If set to fixed, enter the width of the bar in pixels'),
	 "barAlign"	 	 	 	 	  => array('dropdown','If set to fixed, enter alignment of the bar',array ('left','right','center')),
	 "barPadding"	 	 	 	  => array('textbox','Padding of bar from the end of the window'),
	 "minHeartbeat"	 	 	 	  => array('textbox','Minimum poll-time in milliseconds (1 second = 1000 milliseconds)'),
	 "maxHeartbeat"	 	 	 	  => array('textbox','Maximum poll-time in milliseconds'),
	 "longNameLength"	 	 	  => array('textbox','The length after which characters will be truncated in long names'),
	 "shortNameLength"	 	 	  => array('textbox','The length after which characters will be truncated in short names'),
	 "autoLoadModules"	 	 	  => array('choice','If set to yes, modules open in previous page, will open in new page'),
	 "fullName"	 	 	 	 	  => array('choice','If set to yes, both first name and last name will be shown in chat conversations'),
	 "searchDisplayNumber"	 	  => array('textbox','The number of users in Whos Online list after which search bar will be displayed'),
	 "thumbnailDisplayNumber"	  => array('textbox','The number of users in Whos Online list after which thumbnails will be hidden'),
	 "typingTimeout"	 	 	  => array('textbox','The number of milliseconds after which typing to will timeout'),
	 "idleTimeout"	 	 	 	  => array('textbox','The number of seconds after which user will be considered as idle'),
	 "displayOfflineNotification" => array('choice','If yes, user offline notification will be displayed'),
	 "displayOnlineNotification"  => array('choice','If yes, user online notification will be displayed'),
	 "displayBusyNotification"	  => array('choice','If yes, user busy notification will be displayed'),
	 "notificationTime"	 	 	  => array('textbox','The number of milliseconds for which a notification will be displayed'),
	 "announcementTime"	 	 	  => array('textbox','The number of milliseconds for which an announcement will be displayed'),
	 "scrollTime"	 	 	 	  => array('textbox','Can be set to 800 for smooth scrolling when moving from one chatbox to another'),
	 "armyTime"	 	 	 	 	  => array('choice','If set to yes, show time plugin will use 24-hour clock format'),
	 "disableForIE6"	 	 	  => array('choice','If set to yes, CometChat will be hidden in IE6'),
	 "disableForMobileDevices"	  => array('choice','If set to yes, CometChat will be hidden in mobile devices'),
	 "iPhoneView"	 	 	 	  => array('choice','iPhone style messages in chatboxes? (not compatible with dark theme)'),
	 "hideBar"	 	 	 		  => array('choice','Hide bar for non-logged in users?'),
	 "startOffline"	 	 	 	  => array('choice','Load bar in offline mode for all first time users?'),
	 "fixFlash"	 	 	 		  => array('choice','Set to yes, if Adobe Flash animations/ads are appearing on top of the bar (experimental)'),
 	 "lightboxWindows"	 	 	  => array('choice','Set to yes, if you want to use the lightbox style popups'),
	 "sleekScroller"	 	 	  => array('choice','Set to yes, if you want to use the new sleek scroller')

);

function index() {
	global $db;
	global $body;	
	global $languages;
	global $navigation;
	global $lang;
	global $rtl;
	global $options;
	
	$form = '';
	
	foreach ($options as $option => $result) {
		global ${$option};
	
		$form .= '<div class="titlelong" >'.$result[1].'</div><div class="element">';

		if ($result[0] == 'textbox') {
			$form .= '<input type="text" class="inputbox" name="'.$option.'" value="'.${$option}.'">';
		}

		if ($result[0] == 'choice') {
			if (${$option} == 1) {
				$form .= '<input type="radio" name="'.$option.'" value="1" checked>Yes <input type="radio" name="'.$option.'" value="0" >No';	
			} else {
				$form .= '<input type="radio" name="'.$option.'" value="1" >Yes <input type="radio" name="'.$option.'" value="0" checked>No';
			}
			
		}

		if ($result[0] == 'dropdown') {

			$form .= '<select  name="'.$option.'">';
			
			foreach ($result[2] as $opt) {
				if ($opt == ${$option}) {
					$form .= '<option value="'.$opt.'" selected>'.ucwords($opt);	
				} else {
					$form .= '<option value="'.$opt.'">'.ucwords($opt);
				}
			}

			$form .= '</select>';
			
		}
		
		$form .= '</div><div style="clear:both;padding:7px;"></div>';
	}

	$body = <<<EOD
	$navigation
	<form action="?module=settings&action=updatesettings" method="post">
	<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
		<h2>Settings</h2>
		<h3>If you are unsure about any value, please skip them</h3>

		<div>
			<div id="centernav" style="width:700px">
				$form
			</div>

		</div>

		<div style="clear:both;padding:7.5px;"></div>
		<input type="submit" value="Update Settings" class="button">&nbsp;&nbsp;or <a href="?module=settings">cancel</a>
		<input type="hidden" value="{$_SESSION['token']}" name="token">
	</div>

	<div style="clear:both"></div>
	</form>
EOD;

	template();

}

function updatesettings() {
	checktoken();

	global $options;
	
	$data = '';

	foreach ($_POST as $option => $value) {
		$data .= '$'.$option.' = \''.$value.'\';'."\t\t\t// ".$options[$option][1]."\r\n";
	}

	if (!empty($data)) {
		configeditor('SETTINGS',$data,0);
	}

	$_SESSION['cometchat']['error'] = 'Setting details updated successfully';

	header("Location:?module=settings");

}

function whosonline() {
	global $db;
	global $body;	
	global $languages;
	global $navigation;
	global $lang;
	
	$dy = "";
	$dn = "";

	if (defined('DISPLAY_ALL_USERS') && DISPLAY_ALL_USERS == 1) {
		$dy = "checked";
	} else {
		$dn = "checked";
	}

	$body = <<<EOD
	$navigation
	<form action="?module=settings&action=updatewhosonline" method="post">
	<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
		<h2>Who's Online List</h2>
		<h3>You can set CometChat to show either all online users or all friends in the "Who's Online" list.</h3>

		<div>
			<div id="centernav">
				<div class="title" style="width:200px">Show all online users:</div><div class="element"><input type="radio" name="dou" value="1" $dy>Yes <input type="radio" $dn name="dou" value="0" >No</div>
				<div style="clear:both;padding:5px;"></div>
			</div>
			<div id="rightnav">
				<h1>Tips</h1>
				<ul id="modules_availablemodules">
					<li>Displaying all online users is recommended for small sites only.</li>
 				</ul>
			</div>
		</div>

		<div style="clear:both;padding:7.5px;"></div>
		<input type="submit" value="Update Listing" class="button">&nbsp;&nbsp;or <a href="?module=settings">cancel</a>
		<input type="hidden" value="{$_SESSION['token']}" name="token">
	</div>

	<div style="clear:both"></div>
	</form>
EOD;

	template();

}

function updatewhosonline() {
	checktoken();

	$data = 'define(\'DISPLAY_ALL_USERS\',\''.$_POST['dou'].'\');';
	configeditor('DISPLAYSETTINGS',$data,0);

	$_SESSION['cometchat']['error'] = 'Whos online listing updated successfully';

	header("Location:?module=settings&action=whosonline");

}

function disablecometchat() {
	global $db;
	global $body;	
	global $languages;
	global $navigation;
	global $lang;
	
	$dy = "";
	$dn = "";

	if (defined('BAR_DISABLED') && BAR_DISABLED == 1) {
		$dy = "checked";
	} else {
		$dn = "checked";
	}

	$body = <<<EOD
	$navigation
	<form action="?module=settings&action=updatedisablecometchat" method="post">
	<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
		<h2>Disable CometChat</h2>
		<h3>This feature will temporarily disable CometChat on your site.</h3>

		<div>
			<div id="centernav">
				<div class="title" style="width:200px">Disable CometChat:</div><div class="element"><input type="radio" name="dou" value="1" $dy>Yes <input type="radio" $dn name="dou" value="0" >No</div>
				<div style="clear:both;padding:5px;"></div>
			</div>
			<div id="rightnav">
				<h1>Warning</h1>
				<ul id="modules_availablemodules">
					<li>CometChat will stop appearing on your site if this option is set to yes.</li>
 				</ul>
			</div>
		</div>

		<div style="clear:both;padding:7.5px;"></div>
		<input type="submit" value="Update" class="button">&nbsp;&nbsp;or <a href="?module=settings">cancel</a>
		<input type="hidden" value="{$_SESSION['token']}" name="token">
	</div>

	<div style="clear:both"></div>
	</form>
EOD;

	template();

}

function updatedisablecometchat() {
	checktoken();

	$data = 'define(\'BAR_DISABLED\',\''.$_POST['dou'].'\');';
	configeditor('DISABLEBAR',$data,0);

	$_SESSION['cometchat']['error'] = 'CometChat updated successfully';

	header("Location:?module=settings&action=disablecometchat");

}

function guests() {
	global $db;
	global $body;	
	global $languages;
	global $navigation;
	global $lang;
	global $guestsMode;
	global $guestsList;
	global $guestsUsersList;	
	
	$dy = "";
	$dn = "";
	$gL1 = $gL2 = $gL3 = $gUL1 = $gUL2 = $gUL3 = '';

	if ($guestsMode == 1) {
		$dy = "checked";
	} else {
		$dn = "checked";
	}

	if ($guestsList == 1) {	$gL1 = "selected"; }
	if ($guestsList == 2) {	$gL2 = "selected"; }
	if ($guestsList == 3) {	$gL3 = "selected"; }

	if ($guestsUsersList == 1) { $gUL1 = "selected"; }
	if ($guestsUsersList == 2) { $gUL2 = "selected"; }
	if ($guestsUsersList == 3) { $gUL3 = "selected"; }

	$body = <<<EOD
	$navigation
	<form action="?module=settings&action=updateguests" method="post">
	<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
		<h2>Guests Chat</h2>
		<h3>This feature will enable guests to chat on your site without login.</h3>

		<div>
			<div id="centernav">
				<div class="title" style="width:200px">Enable Guest Chat:</div><div class="element"><input type="radio" name="guestsMode" value="1" $dy>Yes <input type="radio" $dn name="guestsMode" value="0" >No</div>
				<div style="clear:both;padding:5px;"></div>
			</div>

			<div id="centernav">
				<div class="title" style="width:200px">In Who's Online list, for guests:</div><div class="element"><select name="guestsList"><option value="1" $gL1>Show only guests</option><option value="2" $gL2>Show only logged in users</option><option value="3" $gL3>Show both</option></select></div>
				<div style="clear:both;padding:5px;"></div>
			</div>

			<div id="centernav">
				<div class="title" style="width:200px">And for logged in users:</div><div class="element"><select name="guestsUsersList"><option value="1" $gUL1>Show only guests</option><option value="2" $gUL2>Show only logged in users</option><option value="3" $gUL3>Show both</option></select></div>
				<div style="clear:both;padding:5px;"></div>
			</div>

			<div id="rightnav">
				<h1>TIPS</h1>
				<ul id="modules_availablemodules">
					<li>If you have configured CometChat to show friends in the Who's Online list, then do not select "Show only logged in users" or "Show both" for Guests.</li>
					<li>Guests will not be able to use certain plugins/modules like chatrooms.</li>
 				</ul>
			</div>
		</div>

		<div style="clear:both;padding:7.5px;"></div>
		<input type="submit" value="Update" class="button">&nbsp;&nbsp;or <a href="?module=settings">cancel</a>
		<input type="hidden" value="{$_SESSION['token']}" name="token">
	</div>

	<div style="clear:both"></div>
	</form>
EOD;

	template();

}

function updateguests() {
	checktoken();

	global $options;
	
	$data = '';

	foreach ($_POST as $option => $value) {
		$data .= '$'.$option.' = \''.$value.'\';'."\r\n";
	}

	if (!empty($data)) {
		configeditor('GUESTS',$data,0);
	}

	$_SESSION['cometchat']['error'] = 'Setting details updated successfully';

	header("Location:?module=settings&action=guests");

}

function banuser() {
	global $db;
	global $body;	
	global $trayicon;
	global $navigation;
	global $bannedUserIDs;
	global $bannedMessage;
	global $bannedWords;

	$bannedids = '';

	foreach ($bannedUserIDs as $b) {
		$bannedids .= $b.',';
	}

	$bannedw = '';

	foreach ($bannedWords as $b) {
		$bannedw .= "'".$b.'\',';
	}

	$body = <<<EOD
	$navigation
	<form action="?module=settings&action=banuserprocess" method="post">
	<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
		<h2>Banned words and users</h2>
		<h3>You can ban users and add words to the abusive list. If you do not know the user's ID, <a href="?module=settings&action=finduser">click here to find out</a></h3>

		<div>
			<div id="centernav">
				<div class="title">Banned Words:</div><div class="element"><input type="text" class="inputbox" name="bannedwords" value="$bannedw"></div>
				<div style="clear:both;padding:5px;"></div>
				<div class="title">Banned User IDs:</div><div class="element"><input type="text" class="inputbox" name="bannedids" value="$bannedids"> <a href="?module=settings&action=finduser">Don't know ID?</a></div>
				<div style="clear:both;padding:5px;"></div>
				<div class="title">Banned Message:</div><div class="element"><input type="text" class="inputbox" name="bannedmessage" value="$bannedMessage"></div>
				<div style="clear:both;padding:5px;"></div>
			</div>
			<div id="rightnav">
				<h1>Warning</h1>
				<ul id="modules_availablemodules">
					<li>Please use comma to separate IDs and words</li>
					<li>Banned users will not be able to use IM and chatroom functionality of CometChat</li>
				</ul>
			</div>
		</div>

		<div style="clear:both;padding:7.5px;"></div>
		<input type="submit" value="Modify" class="button">&nbsp;&nbsp;or <a href="?module=settings">cancel</a>
		<input type="hidden" value="{$_SESSION['token']}" name="token">
	</div>

	<div style="clear:both"></div>

EOD;

	template();
}


function banuserprocess() {
	checktoken();

	if (!empty($_POST['bannedmessage'])) {
		
		$words = array();

		$inputWords = explode(",",$_POST['bannedwords']);

		foreach ($inputWords as $word) {
			$word = str_replace("'","",$word);
			$word = preg_replace("/\s+/"," ",$word);

			if (!empty($word) && $word != "'" && $word != "," && $word != " ") {
				array_push($words,$word);
			}
		}

		$words = "'".implode("','",$words)."'";

		if ($words == "''") { $words = ''; }

		$_SESSION['cometchat']['error'] = 'Banned words and users successfully modified.';
		$_POST['bannedmessage'] = str_replace("'", "", $_POST['bannedmessage']);
		$data = '$bannedWords = array( '.$words.' );'."\r\n".'$bannedUserIDs = array('.$_POST['bannedids'].');'."\r\n".'$bannedMessage = \''.$_POST['bannedmessage'].'\';';
		configeditor('BANNED',$data);
	}
	header("Location:?module=settings&action=banuser");
}

function changeuserpass() {
	global $db;
	global $body;	
	global $trayicon;
	global $navigation;

	$nuser = ADMIN_USER;
	$npass = ADMIN_PASS;

	$body = <<<EOD
	$navigation
	<form action="?module=settings&action=changeuserpassprocess" method="post">
	<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
		<h2>Change administration username and password</h2>
		<h3>If you are unable to login after changing your user/pass, simply edit config.php and find ADMIN_USER</h3>

		<div>
			<div id="centernav">
				<div class="title">New Username:</div><div class="element"><input type="text" class="inputbox" name="nuser" value="$nuser"></div>
				<div style="clear:both;padding:5px;"></div>
				<div class="title">New Password:</div><div class="element"><input type="text" class="inputbox" name="npass" value="$npass"></div>
				<div style="clear:both;padding:5px;"></div>
			</div>
			<div id="rightnav">
				<h1>Warning</h1>
				<ul id="modules_availablemodules">
					<li>Do NOT use ' or \ in your username or password</li>
					<li>Proceed with caution.</li>
 				</ul>
			</div>
		</div>

		<div style="clear:both;padding:7.5px;"></div>
		<input type="submit" value="Change user/pass" class="button">&nbsp;&nbsp;or <a href="?module=settings">cancel</a>
		<input type="hidden" value="{$_SESSION['token']}" name="token">
	</div>

	<div style="clear:both"></div>

EOD;

	template();
}

function changeuserpassprocess() {
	checktoken();

	if (!empty($_POST['nuser']) && !empty($_POST['npass'])) {
		$_SESSION['cometchat']['error'] = 'User/pass successfully modified';
		$data = "define('ADMIN_USER','{$_POST['nuser']}');\r\ndefine('ADMIN_PASS','{$_POST['npass']}');";
		configeditor('ADMIN',$data);
	}
	header("Location:?module=dashboard");
}



function baseurl() {
	global $db;
	global $body;	
	global $trayicon;
	global $navigation;

	$baseurl = BASE_URL;

	$body = <<<EOD
	$navigation
	<form action="?module=settings&action=updatebaseurl" method="post">
	<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
		<h2>Update Base URL</h2>
		<h3>If CometChat is not working on your site, your Base URL might be incorrect.</h3>

		
		<div>
			<div id="centernav">
				<div class="titlelong" style="text-align:left;padding-left:40px;">Our detection algorithm suggests: <b><script>document.write(window.location.pathname.replace("admin/","").replace("admin",""));</script></b></div>
				<div style="clear:both;padding:5px;"></div>
				<div class="title">Base URL:</div><div class="element"><input type="text" class="inputbox" name="baseurl" value="$baseurl"></div>
				<div style="clear:both;padding:5px;"></div>
			</div>
			<div id="rightnav">
				<h1>Warning</h1>
				<ul id="modules_availablemodules">
					<li>If the Base URL is incorrect, CometChat will stop working on your site.</li>
 				</ul>
			</div>
		</div>

		<div style="clear:both;padding:7.5px;"></div>
		<input type="submit" value="Update settings" class="button">&nbsp;&nbsp;or <a href="?module=settings">cancel</a>
		<input type="hidden" value="{$_SESSION['token']}" name="token">
	</div>

	<div style="clear:both"></div>

EOD;

	template();
}

function updatebaseurl() {
	checktoken();

	if (!empty($_POST['baseurl'])) {

		$baseurl = $_POST['baseurl'];
		$baseurl = str_replace('\\','/',$baseurl);

		if ($baseurl[0] != '/') {
			$baseurl = '/'.$baseurl;
		}

		if ($baseurl[strlen($baseurl)-1] != '/') {
			$baseurl = $baseurl.'/';
		}

		$_SESSION['cometchat']['error'] = 'Base URL successfully modified';
		$data = "define('BASE_URL','{$baseurl}');";
		configeditor('BASE URL',$data);
	}
	header("Location:?module=settings&action=baseurl");
}



function comet() {
	global $db;
	global $body;	
	global $trayicon;
	global $navigation;

	$dy = "";
	$dn = "";
	$dy2 = "";
	$dn2 = "";

	if (defined('USE_COMET') && USE_COMET == 1) {
		$dy = "checked";
	} else {
		$dn = "checked";
	}

	if (defined('SAVE_LOGS') && SAVE_LOGS == 1) {
		$dy2 = "checked";
	} else {
		$dn2 = "checked";
	}

	$historylimit = COMET_HISTORY_LIMIT;
	$keya = KEY_A;
	$keyb = KEY_B;
	$keyc = KEY_C;

	$body = <<<EOD
	$navigation
	<form action="?module=settings&action=updatecomet" method="post">
	<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
		<h2>Comet Service</h2>
		<h3>If you are using our hosted Comet service, please enter the details here</h3>

		<div>
			<div id="centernav">
				<div class="title" style="width:200px">Use Comet Service?</div><div class="element"><input type="radio" name="dou" value="1" $dy>Yes <input type="radio" $dn name="dou" value="0" >No</div>
				<div style="clear:both;padding:5px;"></div>
				<div class="title" style="width:200px">Save Logs?</div><div class="element"><input type="radio" name="dou2" value="1" $dy2>Yes <input type="radio" $dn2 name="dou2" value="0" >No</div>
				<div style="clear:both;padding:5px;"></div>
				<div class="title">History limit:</div><div class="element"><input type="text" class="inputbox" name="historylimit" value="$historylimit"></div>
				<div style="clear:both;padding:5px;"></div>
				<div class="title">Key A:</div><div class="element"><input type="text" class="inputbox" name="keya" value="$keya"></div>
				<div style="clear:both;padding:5px;"></div>
				<div class="title">Key B:</div><div class="element"><input type="text" class="inputbox" name="keyb" value="$keyb"></div>
				<div style="clear:both;padding:5px;"></div>
				<div class="title">Key C:</div><div class="element"><input type="text" class="inputbox" name="keyc" value="$keyc"></div>
				<div style="clear:both;padding:5px;"></div>
				
			</div>
			<div id="rightnav">
				<h1>Warning</h1>
				<ul id="modules_availablemodules">
					<li>Make sure that you have subscribed to our service before enabling this service.</li>
					<li>Remember to de-activate the chat history plugin.</li>
					<li>If you face load issues after activating the service, simply switch off Save Logs feature.</li>
					<li>After activation/de-activation be sure to clear your browser cache.</li>
 				</ul>
			</div>
		</div>

		<div style="clear:both;padding:7.5px;"></div>
		<input type="submit" value="Update settings" class="button">&nbsp;&nbsp;or <a href="?module=settings">cancel</a>
		<input type="hidden" value="{$_SESSION['token']}" name="token">
	</div>

	<div style="clear:both"></div>

EOD;

	template();
}

function updatecomet() {
	checktoken();

	$_SESSION['cometchat']['error'] = 'Comet service settings successfully updated';
	$data = "define('USE_COMET','".$_POST['dou']."');\r\ndefine('SAVE_LOGS','".$_POST['dou2']."');\r\ndefine('COMET_HISTORY_LIMIT','".$_POST['historylimit']."');\r\ndefine('KEY_A','".$_POST['keya']."');\r\ndefine('KEY_B','".$_POST['keyb']."');\r\ndefine('KEY_C','".$_POST['keyc']."');";
	configeditor('COMET',$data);
	
	header("Location:?module=settings&action=comet");
}

function finduser() {
	global $db;
	global $body;	
	global $navigation;

	$body = <<<EOD
	$navigation
	<form action="?module=settings&action=searchlogs" method="post" enctype="multipart/form-data">
	<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
		<h2>Find User ID</h2>
		<h3>You can search by username.</h3>

		<div>
			<div id="centernav">
				<div class="title">Username:</div><div class="element"><input type="text" class="inputbox" name="susername"></div>
				<div style="clear:both;padding:5px;"></div>
			</div>
		</div>

		<div style="clear:both;padding:7.5px;"></div>
		<input type="submit" value="Search Database" class="button">&nbsp;&nbsp;or <a href="?module=settings&action=banuser">cancel</a>
		<input type="hidden" value="{$_SESSION['token']}" name="token">
	</div>

	<div style="clear:both"></div>

EOD;

	template();

}

function searchlogs() {
	checktoken();

	global $usertable_userid;
	global $usertable_username;
	global $usertable;
	global $navigation;
	global $body;
	
	$username = $_POST['susername'];

	if (empty($username)) {
		// Base 64 Encoded
		$username = 'Q293YXJkaWNlIGFza3MgdGhlIHF1ZXN0aW9uIC0gaXMgaXQgc2FmZT8NCkV4cGVkaWVuY3kgYXNrcyB0aGUgcXVlc3Rpb24gLSBpcyBpdCBwb2xpdGljPw0KVmFuaXR5IGFza3MgdGhlIHF1ZXN0aW9uIC0gaXMgaXQgcG9wdWxhcj8NCkJ1dCBjb25zY2llbmNlIGFza3MgdGhlIHF1ZXN0aW9uIC0gaXMgaXQgcmlnaHQ/DQpBbmQgdGhlcmUgY29tZXMgYSB0aW1lIHdoZW4gb25lIG11c3QgdGFrZSBhIHBvc2l0aW9uDQp0aGF0IGlzIG5laXRoZXIgc2FmZSwgbm9yIHBvbGl0aWMsIG5vciBwb3B1bGFyOw0KYnV0IG9uZSBtdXN0IHRha2UgaXQgYmVjYXVzZSBpdCBpcyByaWdodC4=';
	}

	$sql = ("select $usertable_userid id, $usertable_username username from $usertable where $usertable_username LIKE '%".mysql_real_escape_string(sanitize_core($username))."%'");
	$query = mysql_query($sql);

	$userslist = '';

	while ($user = mysql_fetch_array($query)) {
		if (function_exists('processName')) {
			$user['username'] = processName($user['username']);
		}

		$userslist .= '<li class="ui-state-default"><span style="font-size:11px;float:left;margin-top:2px;margin-left:5px;">'.$user['username'].' - '.$user['id'].'</span><div style="clear:both"></div></li>';
	}

	$body = <<<EOD
	$navigation

	<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
		<h2>Search results</h2>
		<h3>Please find the user id next to each username. <a href="?module=settings&action=finduser">Click here to search again</a></h3>

		<div>
			<ul id="modules_logs">
				$userslist
			</ul>
		</div>

		<div style="clear:both;padding:7.5px;"></div>
	</div>

	<div style="clear:both"></div>

EOD;
	
	template();
}
