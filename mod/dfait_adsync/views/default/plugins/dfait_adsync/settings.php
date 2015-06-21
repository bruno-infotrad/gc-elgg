<?php
$key = elgg_get_config('dbpass');

$passwd = $vars['entity']->adsync_teaminfo_password;
$teaminfo_password = mcrypt_decrypt(MCRYPT_3DES, $key, base64_decode($passwd), MCRYPT_MODE_ECB);
$block = mcrypt_get_block_size('tripledes', 'ecb');
$pad = ord($teaminfo_password[($len = strlen($teaminfo_password)) - 1]);
$teaminfo_password = substr($teaminfo_password, 0, strlen($teaminfo_password) - $pad);

$passwd = $vars['entity']->adsync_ldap_bind_password;
$ldap_password = mcrypt_decrypt(MCRYPT_3DES, $key, base64_decode($passwd), MCRYPT_MODE_ECB);
$block = mcrypt_get_block_size('tripledes', 'ecb');
$pad = ord($ldap_password[($len = strlen($ldap_password)) - 1]);
$ldap_password = substr($ldap_password, 0, strlen($ldap_password) - $pad);

?>
<div class="elgg-module elgg-module-inline">
	<div class="elgg-head">
		<h3>
			<?php echo elgg_echo('adsync:actions:title'); ?>
		</h3>
	</div>
	<div class="elgg-body profile-manager-actions">
		<?php 
			echo elgg_view("output/url", 
							array("text" => elgg_echo("adsync:actions:sync_all"), 
								  "title" => elgg_echo("adsync:actions:sync_all:description"), 
								  "href" => "/dfait_adsync/sync/all", 
								  "confirm" => elgg_echo("adsync:actions:sync_all:confirm"), 
								  "class" => "elgg-button elgg-button-action")); 
			echo elgg_view("output/url",
							array("text" => elgg_echo("adsync:actions:sync_changes"),
									"title" => elgg_echo("adsync:actions:sync_changes:description"),
									"href" => "/dfait_adsync/sync/changes",
									"confirm" => elgg_echo("adsync:actions:sync_changes:confirm"),
									"class" => "elgg-button elgg-button-action"));
		?>
	</div>
</div>

<div class="elgg-module elgg-module-inline">
	<div class="elgg-head">
		<h3>
			<?php echo elgg_echo('adsync:config:title'); ?>
		</h3>
	</div>
	<div class="elgg-body profile-manager-actions">
		<p>
		    <fieldset style="border: 1px solid; padding: 15px; margin: 0 10px 0 10px">
		        <legend><?php echo elgg_echo('adsync:settings:label:profile_section');?></legend>
		        
				<label for="params[adsync_profile_type_id]"><?php echo elgg_echo('adsync:settings:label:profile_type_id');?></label><br/>
				<div class="example"><?php echo elgg_echo('adsync:settings:help:profile_type_id');?></div>
				<input type="text" name="params[adsync_profile_type_id]" value="<?php echo $vars['entity']->adsync_profile_type_id;?>"/><br/>

				<label for="params[adsync_profile_default_value]"><?php echo elgg_echo('adsync:settings:label:profile_default_value');?></label><br/>
				<div class="example"><?php echo elgg_echo('adsync:settings:help:profile_default_value');?></div>
				<input type="text" name="params[adsync_profile_default_value]" value="<?php echo $vars['entity']->adsync_profile_default_value;?>"/><br/>
			</fieldset>
		</p>
		<p>
		    <fieldset style="border: 1px solid; padding: 15px; margin: 0 10px 0 10px">
		        <legend><?php echo elgg_echo('adsync:settings:label:teaminfo_section');?></legend>
		        
				<label for="params[adsync_teaminfo_server]"><?php echo elgg_echo('adsync:settings:label:teaminfo_server');?></label><br/>
				<div class="example"><?php echo elgg_echo('adsync:settings:help:teaminfo_server');?></div>
				<input type="text" name="params[adsync_teaminfo_server]" value="<?php echo $vars['entity']->adsync_teaminfo_server;?>"/><br/>
				
				<label for="params[adsync_teaminfo_username]"><?php echo elgg_echo('adsync:settings:label:teaminfo_username');?></label><br/>
				<div class="example"><?php echo elgg_echo('adsync:settings:help:teaminfo_username');?></div>
				<input type="text" name="params[adsync_teaminfo_username]" value="<?php echo $vars['entity']->adsync_teaminfo_username;?>"/><br/>
				
				<label for="params[adsync_teaminfo_password]"><?php echo elgg_echo('adsync:settings:label:teaminfo_password');?></label><br/>
				<div class="example"><?php echo elgg_echo('adsync:settings:help:teaminfo_password');?></div>
				<input type="password" name="params[adsync_teaminfo_password]" value="<?php echo $teaminfo_password;?>"/><br/>
			</fieldset>
		</p>
		<p>
		    <fieldset style="border: 1px solid; padding: 15px; margin: 0 10px 0 10px">
		        <legend><?php echo elgg_echo('adsync:settings:label:ldap_section');?></legend>
		        
				<label for="params[adsync_ldap_server]"><?php echo elgg_echo('adsync:settings:label:ldap_server');?></label><br/>
				<div class="example"><?php echo elgg_echo('adsync:settings:help:ldap_server');?></div>
				<input type="text" name="params[adsync_ldap_server]" value="<?php echo $vars['entity']->adsync_ldap_server;?>"/><br/>
				
				<label for="params[adsync_ldap_server_port]"><?php echo elgg_echo('adsync:settings:label:ldap_server_port');?></label><br/>
				<div class="example"><?php echo elgg_echo('adsync:settings:help:ldap_server_port');?></div>
				<input type="text" name="params[adsync_ldap_server_port]" value="<?php echo $vars['entity']->adsync_ldap_server_port;?>"/><br/>
				
				<label for="params[adsync_ldap_proto_version]"><?php echo elgg_echo('adsync:settings:label:ldap_proto_version');?></label><br/>
				<div class="example"><?php echo elgg_echo('adsync:settings:help:ldap_proto_version');?></div>
		        <select name="params[adsync_ldap_proto_version]">
		            <option value="1" <?php if ($vars['entity']->adsync_ldap_proto_version == 1) echo " selected=\"selected\" "; ?>>1</option>
		            <option value="2" <?php if ($vars['entity']->adsync_ldap_proto_version == 2) echo " selected=\"selected\" "; ?>>2</option>
		            <option value="3" <?php if ((!$vars['entity']->adsync_ldap_proto_version) || ($vars['entity']->adsync_ldap_proto_version == 3)) echo " selected=\"selected\" "; ?>>3</option>
		        </select><br/>
				
				<label for="params[adsync_ldap_bind_dn]"><?php echo elgg_echo('adsync:settings:label:ldap_bind_dn');?></label><br/>
				<div class="example"><?php echo elgg_echo('adsync:settings:help:ldap_bind_dn');?></div>
				<input type="text" name="params[adsync_ldap_bind_dn]" value="<?php echo $vars['entity']->adsync_ldap_bind_dn;?>"/><br/>

				<label for="params[adsync_ldap_bind_password]"><?php echo elgg_echo('adsync:settings:label:ldap_bind_password');?></label><br/>
				<div class="example"><?php echo elgg_echo('adsync:settings:help:ldap_bind_password');?></div>
				<input type="password" name="params[adsync_ldap_bind_password]" value="<?php echo $ldap_password;?>"/><br/>

		        <label for="params[adsync_ldap_field_mapping]"><?php echo elgg_echo('adsync:settings:label:ldap_field_mapping');?></label><br/>
		        <div class="example"><?php echo elgg_echo('adsync:settings:help:ldap_field_mapping');?></div>
		        <input type="text" size="50" name="params[adsync_ldap_field_mapping]" value="<?php echo $vars['entity']->adsync_ldap_field_mapping;?>"/><br/>
		
		        <label for="params[adsync_ldap_create_user]"><?php echo elgg_echo('adsync:settings:label:ldap_create_user');?></label><br/>
		        <div class="example"><?php echo elgg_echo('adsync:settings:help:ldap_create_user');?></div>
		        <select name="params[adsync_ldap_create_user]">
		            <option value="on" <?php if ($vars['entity']->adsync_ldap_create_user == 'on') echo " selected=\"selected\" "; ?>>Enabled</option>
		            <option value="off" <?php if ($vars['entity']->adsync_ldap_create_user == 'off') echo " selected=\"selected\" "; ?>>Disabled</option>
		        </select><br/>
				
				</fieldset>
		</p>
	</div>
</div>
