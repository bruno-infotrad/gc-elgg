<?php 
$subject = elgg_extract("subject", $vars);
$message = nl2br(elgg_extract("body", $vars));
$language = elgg_extract("language", $vars, get_current_language());
//$recipient = elgg_extract("recipient", $vars);

//$site = elgg_get_site_entity();
//$site_url = elgg_get_site_url();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<base target="_blank" />
		
		<?php 
			if(!empty($subject)){ 
				echo "<title>" . $subject . "</title>\n";
			}
		?>
	</head>
	<body>
		<style type="text/css">
			body {
				font: 80%/1.4 "Lucida Grande", Verdana, sans-serif;
				color: #333333;				
			}
			
			a {
				color: #4690d6;
			}
			
			#notification_container {
				padding: 20px 0;
				width: 600px;
				margin: 0 auto;
			}
		
			#notification_header {
				text-align: right;
				padding: 0 0 10px;
			}
			
			#notification_header a {
				text-decoration: none;
				font-weight: bold;
				color: #0054A7;
				font-size: 1.5em;
			} 
		
			#notification_wrapper {
				background: #DEDEDE;
				padding: 10px;
			}
			
			#notification_wrapper h2 {
				margin: 5px 0 5px 10px;
				color: #0054A7;
				font-size: 1.35em;
				line-height: 1.2em;
			}
			
			#notification_content {
				background: #FFFFFF;
				padding: 10px;
			}
			
			#notification_footer {
				
				margin: 10px 0 0;
				background: #B6B6B6;
				padding: 10px;
				text-align: right;
			}
			
			#notification_footer_logo {
				float: left;
			}
			
			#notification_footer_logo img {
				border: none;
			}
			
			.clearfloat {
				clear:both;
				height:0;
				font-size: 1px;
				line-height: 0px;
			}
			
		</style>
	
		<div id="notification_container">
			<div id="notification_wrapper">
				<div id="notification_content">
					<?php echo $message; ?>
					<p><p>
					<?php 
					if (preg_match ('/\/groups\/mail\//',$_SERVER['HTTP_REFERER'])) {
						$note=elgg_echo("htm_email_handler:group_send");
					} else {
						$notification_url = elgg_get_site_url().'notifications/personal';
						$note=elgg_echo("htm_email_handler:note", array($notification_url));
						$note.=elgg_echo("htm_email_handler:note2");
					}
					echo $note;
					?>
				</div>
			</div>
		</div>
	</body>
</html>
