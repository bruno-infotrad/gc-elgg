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
	<a href="?module=chatrooms">Chatrooms</a>
	<a href="?module=chatrooms&action=newchatroom">Add new chatroom</a>
	</div>
EOD;

function index() {
	global $db;
	global $body;	
	global $trayicon;
	global $navigation;

	require dirname(dirname(__FILE__)).'/modules/chatrooms/config.php';

	$sql = ("select * from cometchat_chatrooms where (('".getTimeStamp()."'-lastactivity<".$chatroomTimeout.") or createdby = 0) order by name asc");
	$query = mysql_query($sql);
	if (defined('DEV_MODE') && DEV_MODE == '1') { echo mysql_error(); }

	$chatroomlist = '';
	
	while ($chatroom = mysql_fetch_array($query)) {

		$type = '';
		
		if ($chatroom['type'] == '1') {
			$type = ' (password protected)';
		} else if ($chatroom['type'] == '2') {
			$type = ' (invitation only)';
		}

		$typeuser = '';

 
		if ($chatroom['createdby'] != 0) {
			$typeuser = ' (user created)';
		}

		$extra = '';

		if (empty($type)) {
			$extra = '<a href="../modules/chatrooms/index.php?id='.$chatroom['id'].'" target="_blank" style="margin-right:5px;"><img src="images/link.png" title="Direct link to chatroom"></a><a onclick="javascript:embed_link(\''.BASE_URL.'modules/chatrooms/index.php?id='.$chatroom['id'].'\',\'500\',\'300\');" href="#" style="margin-right:5px;"><img src="images/embed.png" title="Embed code for chatroom"></a>';
		}

		$chatroomlist .= '<li class="ui-state-default"><span style="font-size:11px;float:left;margin-top:3px;margin-left:5px;">'.$chatroom['name'].' (ID: '. $chatroom['id'].')'.$type.$typeuser.'</span><span style="font-size:11px;float:right;margin-top:0px;margin-right:5px;">'.$extra.'<a href="?module=chatrooms&action=deletechatroom&data='.$chatroom['id'].'&token='.$_SESSION['token'].'"><img src="images/remove.png" title="Remove Chatroom"></a></span><div style="clear:both"></div></li>';
	}

	$body = <<<EOD
	$navigation

	<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
		<h2>Chatrooms</h2>
		<h3>Displaying recent user created and permanent chatrooms</h3>

		<div>
			<ul id="modules_chatrooms">
				$chatroomlist
			</ul>
			<div id="rightnav" style="margin-top:5px">
				<h1>Tips</h1>
				<ul id="modules_chatroomtips">
					<li>When you add a chatroom, it will be displayed live to all online chatroom users.</li>
				</ul>
			</div>
		</div>

		<div style="clear:both;padding:7.5px;"></div>
	</div>

	<div style="clear:both"></div>

EOD;

	template();

}

function deletechatroom() {
	checktoken();

	if (!empty($_GET['data'])) {
		$sql = ("delete from cometchat_chatrooms where id = '".mysql_real_escape_string(sanitize_core($_GET['data']))."'");
		$query = mysql_query($sql);
	}

	header("Location:?module=chatrooms");
}

function newchatroom() {
	global $db;
	global $body;	
	global $trayicon;
	global $navigation;

	$body = <<<EOD
	$navigation
	<form action="?module=chatrooms&action=newchatroomprocess" method="post" enctype="multipart/form-data">
	<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
		<h2>New chatroom</h2>
		<h3>You can add permanent chatrooms using the following form</h3>

		<div>
			<div id="centernav">
				<div class="title">Chatroom:</div><div class="element"><input type="text" class="inputbox" name="chatroom"></div>
				<div style="clear:both;padding:5px;"></div>
				<div class="title">Type:</div><div class="element"><select class="inputbox" name="type"><option value="0">Public room<option  value="1">Password protected room</select></div>
				<div style="clear:both;padding:5px;"></div>
				<div class="title">If password protected, enter password:</div><div class="element"><input type="text" class="inputbox" name="ppassword"></div>
			</div>
			<div id="rightnav">
				<h1>Warning</h1>
				<ul id="modules_availablemodules">
					<li>Your chatrooms will be shown live to all online users. Double check before proceeding.</li>
 				</ul>
			</div>
		</div>

		<div style="clear:both;padding:7.5px;"></div>
		<input type="submit" value="Add Chatroom" class="button">&nbsp;&nbsp;or <a href="?module=chatrooms">cancel</a>
		<input type="hidden" value="{$_SESSION['token']}" name="token">
	</div>

	<div style="clear:both"></div>

EOD;

	template();

}

function newchatroomprocess() {
	checktoken();

	$chatroom = $_POST['chatroom'];
	$type = $_POST['type'];
	$password = $_POST['ppassword'];

	if (!empty($password) && ($type == 1 || $type == 2)) {
		$password = sha1($password);
	} else {
		$password = '';
	}

	$sql = ("insert into cometchat_chatrooms (name,createdby,lastactivity,password,type) values ('".mysql_real_escape_string(sanitize_core($chatroom))."', '0','".getTimeStamp()."','".mysql_real_escape_string($password)."','".mysql_real_escape_string($type)."')");
	$query = mysql_query($sql);

	header( "Location: ?module=chatrooms" ); 
}