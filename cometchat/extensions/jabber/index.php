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

include dirname(dirname(dirname(__FILE__)))."/plugins.php";

include dirname(__FILE__)."/lang/en.php";

if (file_exists(dirname(__FILE__)."/lang/".$lang.".php")) {
	include dirname(__FILE__)."/lang/".$lang.".php";
}

include dirname(__FILE__)."/config.php";

$domain = '';
if (!empty($_GET['basedomain'])) {
	$domain = $_GET['basedomain'];
}

$embed = '';
$embedcss = '';
$close = 'window.close();';
$before = 'window.opener';
$before2 = 'window.top';

if (!empty($_GET['embed']) && $_GET['embed'] == 'web') {
	$embed = 'web';
	$before = 'parent';
	$before2 = 'parent';
	$embedcss = 'embed'; 
	$close = "parent.closeCCPopup('jabber');"; 
}

if (!empty($_GET['embed']) && $_GET['embed'] == 'desktop') {
	$embed = 'desktop';
	$before = 'parentSandboxBridge';
	$before2 = 'parentSandboxBridge';
	$embedcss = 'embed';
	$close = "parentSandboxBridge.closeCCPopup('jabber');";
}

if (!empty($_GET['session'])) {
	echo <<<EOD
	<script>
	{$before2}.location.href = location.href.replace('session','sessiondata');
	</script>
EOD;
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<title><?php echo $jabber_language[0];?><?php echo $jabberName;?><?php echo $jabber_language[15];?></title> 
<link type="text/css" rel="stylesheet" media="all" href="../../css.php?type=extension&name=jabber" /> 
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script>

function login() {
	var username = $('#username').val();
	var password = $('#password').val();

	$('#gtalk').css('display','none');
	$('#loader').css('display','block');

	$.getJSON("<?php echo $cometchatServer;?>j?json_callback=?", {'action':'login', username: username, password: password, server: '<?php echo $jabberServer;?>', port: '<?php echo $jabberPort;?>'} , function(data){
		if (data[0].error == '0') {
			$.cookie('cc_jabber','true',{ path: '/' });
			$.cookie('cc_jabber_id',data[0].msg,{ path: '/' });
			$.cookie('cc_jabber_type','gtalk',{ path: '/' });
			$('.container_body_2').remove();
			$('#gtalk_box').html('<span><?php echo $jabber_language[7];?></span>');

			setTimeout(function() {
				try {
					<?php echo $before;?>.jqcc.ccjabber.process();
					<?php echo $close;?>	
				} catch (e) {
					crossDomain();
				}
			}, 4000);


			
		} else {
			alert('<?php echo $jabber_language[9];?>');

			$('#gtalk').css('display','block');
			$('#loader').css('display','none');
		}
	});

	return false;
}

function login_facebook(session) {
	var currenttime = new Date();
	currenttime = parseInt(currenttime.getTime()/1000);

	$.getJSON("<?php echo $cometchatServer;?>j?json_callback=?", {'action':'login', username: 'dummy'+currenttime, password: 'dummy'+currenttime, session_key: session, server: 'chat.facebook.com', port: '5222', id: '<?php echo $facebookAppId;?>', key: '<?php echo $facebookSecretKey;?>'} , function(data){
		if (data[0].error == '0') {
			$.cookie('cc_jabber','true',{ path: '/' });
			$.cookie('cc_jabber_id',data[0].msg,{ path: '/' });
			$.cookie('cc_jabber_type','facebook',{ path: '/' });
	
			setTimeout(function() {
				try {
					<?php echo $before;?>.jqcc.ccjabber.process();
					<?php echo $close;?>				
				} catch (e) {
					crossDomain();
				}
			}, 4000);

		} else {
			alert('<?php echo $jabber_language[9];?>');
		}
	});

	return false;
}

$(document).ready(function() {
//	$.cookie('cc_jabber','false',{ path: '/' });
//	$.getJSON("<?php echo $cometchatServer;?>j?json_callback=?", {'action':'logout'});
});

function crossDomain() {
	var ts = Math.round((new Date()).getTime() / 1000);
	location.href= '//<?php echo $domain;?>/chat.htm?ts='+ts+'&jabber='+$.cookie('cc_jabber')+'&jabber_type='+$.cookie('cc_jabber_type')+'&jabber_id='+$.cookie('cc_jabber_id');
}

// Copyright (c) 2006 Klaus Hartl (stilbuero.de)
// http://www.opensource.org/licenses/mit-license.php

jQuery.cookie=function(a,b,c){if(typeof b!='undefined'){c=c||{};if(b===null){b='';c.expires=-1}var d='';if(c.expires&&(typeof c.expires=='number'||c.expires.toUTCString)){var e;if(typeof c.expires=='number'){e=new Date();e.setTime(e.getTime()+(c.expires*24*60*60*1000))}else{e=c.expires}d='; expires='+e.toUTCString()}var f=c.path?'; path='+(c.path):'';var g=c.domain?'; domain='+(c.domain):'';var h=c.secure?'; secure':'';document.cookie=[a,'=',encodeURIComponent(b),d,f,g,h].join('')}else{var j=null;if(document.cookie&&document.cookie!=''){var k=document.cookie.split(';');for(var i=0;i<k.length;i++){var l=jQuery.trim(k[i]);if(l.substring(0,a.length+1)==(a+'=')){j=decodeURIComponent(l.substring(a.length+1));break}}}return j}};


</script>
</head>

<body><form name="upload" onsubmit="return login();">
<div class="container">
<div class="container_title <?php echo $embedcss;?>"><?php echo $jabber_language[1];?></div>

<div class="container_body <?php echo $embedcss;?>">

<?php if(empty($_GET['sessiondata'])):?>

	<div class="container_body_1">
		<span><h1><?php echo $jabberName;?> <?php echo $jabber_language[4];?></h1></span><br/>
		<div id="gtalk_box">
			<table>
				<tr>
					<td><?php echo $jabber_language[2];?></td>
					<td><input type="text" id="username" name="username"></td>
				</tr>
				<tr>
					<td><?php echo $jabber_language[3];?></td>
					<td><input type="password" id="password" name="password"></td>
				</tr>
				<tr>
					<td colspan="2"><input type="submit" value="<?php echo $jabber_language[6];?> <?php echo $jabberName;?>" id="gtalk"><div id="loader"></div></td>
				</tr>
			</table>
		</div>
	</div>

	<div class="container_body_2">
		<span><h1><?php echo $jabber_language[5];?></h1></span><br/>
	<script>
		String.prototype.replaceAll=function(s1, s2) {return this.split(s1).join(s2)};
		var currenttime = new Date();
		currenttime = parseInt(currenttime.getTime());

		document.write('<iframe src="<?php echo $cometchatServer;?>facebook.jsp?time='+currenttime+'&id=<?php echo $facebookAppId;?>&r='+location.href.replaceAll('&','AND').replaceAll('?','QUESTION')+'" frameborder="0" border="0" width="150" height="30"></iframe>');
	</script>

	</div>
<?php else:?>
	<div class="container_body_1">
		<span><?php echo $jabber_language[7];?></span>
	</div>
	<script>
		$(document).ready(function() {
			login_facebook('<?php echo $_GET['sessiondata'];?>');
		});
	</script>
<?php endif;?>

<div style="clear:both"></div>

</div>
</div>

</form>


</body>
</html>