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
	<a href="?module=logs">Logs</a>
	</div>
EOD;

function index() {
	global $db;
	global $body;	
	global $navigation;

	$body = <<<EOD
	$navigation
	<form action="?module=logs&action=searchlogs" method="post" enctype="multipart/form-data">
	<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
		<h2>Search logs</h2>
		<h3>You can search by username or user ID. Please fill in atleast one field below.</h3>

		<div>
			<div id="centernav">
				<div class="title">User ID:</div><div class="element"><input type="text" class="inputbox" name="userid"></div>
				<div style="clear:both;padding:5px;"></div>
				<div class="title">Username:</div><div class="element"><input type="text" class="inputbox" name="susername"></div>
				<div style="clear:both;padding:5px;"></div>
			</div>
			<div id="rightnav">
				<h1>Warning</h1>
				<ul id="modules_logtips">
					<li>This feature is resource intensive. Please use judiciously.</li>
 				</ul>
			</div>
		</div>

		<div style="clear:both;padding:7.5px;"></div>
		<input type="submit" value="Search Logs" class="button">&nbsp;&nbsp;or <a href="?module=logs">cancel</a>
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
	
	$userid = $_POST['userid'];
	$username = $_POST['susername'];

	if (empty($username)) {
		// Base 64 Encoded
		$username = 'Q293YXJkaWNlIGFza3MgdGhlIHF1ZXN0aW9uIC0gaXMgaXQgc2FmZT8NCkV4cGVkaWVuY3kgYXNrcyB0aGUgcXVlc3Rpb24gLSBpcyBpdCBwb2xpdGljPw0KVmFuaXR5IGFza3MgdGhlIHF1ZXN0aW9uIC0gaXMgaXQgcG9wdWxhcj8NCkJ1dCBjb25zY2llbmNlIGFza3MgdGhlIHF1ZXN0aW9uIC0gaXMgaXQgcmlnaHQ/DQpBbmQgdGhlcmUgY29tZXMgYSB0aW1lIHdoZW4gb25lIG11c3QgdGFrZSBhIHBvc2l0aW9uDQp0aGF0IGlzIG5laXRoZXIgc2FmZSwgbm9yIHBvbGl0aWMsIG5vciBwb3B1bGFyOw0KYnV0IG9uZSBtdXN0IHRha2UgaXQgYmVjYXVzZSBpdCBpcyByaWdodC4=';
	}

	$sql = ("select $usertable_userid id, $usertable_username username from $usertable where $usertable_username LIKE '%".mysql_real_escape_string(sanitize_core($username))."%' or $usertable_userid = '".mysql_real_escape_string(sanitize_core($userid))."'");
	$query = mysql_query($sql);

	$userslist = '';

	while ($user = mysql_fetch_array($query)) {
		if (function_exists('processName')) {
			$user['username'] = processName($user['username']);
		}

		$userslist .= '<li class="ui-state-default" onclick="javascript:logs_gotouser(\''.$user['id'].'\');"><span style="font-size:11px;float:left;margin-top:2px;margin-left:5px;">'.$user['username'].'</span><div style="clear:both"></div></li>';
	}

	$body = <<<EOD
	$navigation

	<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
		<h2>Logs</h2>
		<h3>Please select a user from below. <a href="?module=logs">Click here to search again</a></h3>

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

function viewuser() {
	checktoken();

	global $db;
	global $body;	
	global $trayicon;
	global $navigation;
	global $usertable_userid;
	global $usertable_username;
	global $usertable;

	$userid = $_GET['data'];

	$sql = ("select $usertable_username username from $usertable where $usertable_userid = '".mysql_real_escape_string($userid)."'");
	$query = mysql_query($sql); 
	$usern = mysql_fetch_array($query);

	$sql = ("(select distinct(f.$usertable_userid) id, f.$usertable_username username  from cometchat m1, $usertable f
	where  f.$usertable_userid = m1.from and m1.to = '".mysql_real_escape_string($userid)."')
	UNION
	(select distinct(f.$usertable_userid) id, f.$usertable_username username  from cometchat m1, $usertable f
	where  f.$usertable_userid = m1.to and m1.from = '".mysql_real_escape_string($userid)."')
	order by username asc");

	$query = mysql_query($sql); 

	$userslist = '';

	if (function_exists('processName')) {
		$usern['username'] = processName($usern['username']);
	}

	while ($user = mysql_fetch_array($query)) {
		if (function_exists('processName')) {
			$user['username'] = processName($user['username']);
		}

		$userslist .= '<li class="ui-state-default" onclick="javascript:logs_gotouserb(\''.$userid.'\',\''.$user['id'].'\');"><span style="font-size:11px;float:left;margin-top:2px;margin-left:5px;">'.$user['username'].'</span><div style="clear:both"></div></li>';
	}

	$body = <<<EOD
	$navigation
	<form action="?module=logs&action=newlogprocess" method="post" enctype="multipart/form-data">
	<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
		<h2>Log for {$usern['username']}</h2>
		<h3>Select a user between whom you want to view the conversation.</h3>

		<div>
			<ul id="modules_logs">
				$userslist
			</ul>
		</div>
	</div>

	<div style="clear:both"></div>

EOD;

	template();

}


function viewuserconversation() {
	checktoken();

	global $db;
	global $body;	
	global $trayicon;
	global $navigation;
	global $usertable_userid;
	global $usertable_username;
	global $usertable;

	$userid = $_GET['data'];
	$userid2 = $_GET['data2'];

	$sql = ("select $usertable_username username from $usertable where $usertable_userid = '".mysql_real_escape_string($userid)."'");
	$query = mysql_query($sql); 
	$usern = mysql_fetch_array($query);

	$sql = ("select $usertable_username username from $usertable where $usertable_userid = '".mysql_real_escape_string($userid2)."'");
	$query = mysql_query($sql); 
	$usern2 = mysql_fetch_array($query);

	$sql = ("(select m.*  from cometchat m where  (m.from = '".mysql_real_escape_string($userid)."' and m.to = '".mysql_real_escape_string($userid2)."') or (m.to = '".mysql_real_escape_string($userid)."' and m.from = '".mysql_real_escape_string($userid2)."'))
	order by id desc");

	$query = mysql_query($sql); 

	$userslist = '';

	while ($chat = mysql_fetch_array($query)) {
		$time = date('g:iA M dS', $chat['sent']);

		if ($userid == $chat['from']) {
			$dir = '>';
		} else {
			$dir = '<';
		}

		$userslist .= '<li class="ui-state-default"><span style="font-size:11px;float:left;margin-top:2px;margin-left:0px;width:10px;margin-right:10px;color:#fff;background-color:#333;padding:0px;-moz-border-radius:5px;-webkit-border-radius:5px;"><b>'.$dir.'</b></span><span style="font-size:11px;float:left;margin-top:2px;margin-left:5px;width:560px;">&nbsp; '.$chat['message'].'</span><span style="font-size:11px;float:right;width:100px;overflow:hidden;margin-top:2px;margin-left:10px;">'.$time.'</span><div style="clear:both"></div></li>';
	}

	if (function_exists('processName')) {
			$usern['username'] = processName($usern['username']);
			$usern2['username'] = processName($usern2['username']);
	}

	$body = <<<EOD
	$navigation
	<form action="?module=logs&action=newlogprocess" method="post" enctype="multipart/form-data">
	<div id="rightcontent" style="float:left;width:720px;border-left:1px dotted #ccc;padding-left:20px;">
		<h2>Log between {$usern['username']} and {$usern2['username']}</h2>
		<h3>To see other conversations of {$usern['username']}, <a href="?module=logs&action=viewuser&data={$userid}">click here</a></h3>

		<div>
			<ul id="modules_logslong">
				$userslist
			</ul>
		</div>
	</div>

	<div style="clear:both"></div>

EOD;

	template();

}