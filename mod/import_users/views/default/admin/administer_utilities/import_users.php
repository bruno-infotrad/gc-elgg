<?php 
	/**
	* Import Users
	* 
	* Settings and Action screen.
	* 
	* @package import_users
	* @author Sebastien Routier
	* @copyright Sebastien Routier 2013
	* @license http://opensource.org/licenses/GPL-3.0 (GPL-3.0)
	*/
?>
<div class="elgg-module elgg-module-inline">
	<div class="elgg-head">
		<h3>
			<?php echo elgg_echo('import_users:actions:title'); ?>
			<span class='import_users_more_info' id='more_info_actions'></span>
		</h3>
	</div>
	<div class="elgg-body import-users-actions">
		<?php 
		
			$form_body = "<div>" . elgg_echo("import_users:actions:import_users:description") . "</div>";
			$form_body .= "<div>" . elgg_echo("import_users:actions:import_users:help:csv_format") . "</div>";
			$form_body .= "<div>" . elgg_echo("import_users:actions:import_users:help:csv_command") . "</div>";
			$form_body .= elgg_view("input/file", array("name" => "importUserFile"));
			$form_body .= elgg_view("input/submit", array("value" => elgg_echo("import_users:actions:import_users_csv:upload")));

			$form = elgg_view("input/form", array("action" => "action/import_users/import_users_csv", "id" => "importUsersCSVForm", "body" => $form_body, "enctype" => "multipart/form-data"));

			echo $form;
		?>
	</div>
</div>
