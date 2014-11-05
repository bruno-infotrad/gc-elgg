<?php
 
	$org_members_block = "";

	$org_members = $vars['org_members'];
	
	$spacer = "";
	foreach ($org_members as $person) {

		$person_block = "";
		$output_type = "url";
		$site_url = elgg_get_site_url();
		$person_id = $person->dfaitedsid;
		$person_displayname = $person->displayname;
		$person_url_vars = array(
				'text' => $person_displayname,
				'href' => $site_url . "profile/" . $person_id,
		);
		
		// build result
		$person_block .= "<div class='elgg-profile-longfield'>";
		$person_block .= $spacer . elgg_view("output/" . $output_type, $person_url_vars);
		$person_block .= "</div>";
		
		$org_members_block .= $person_block;
		$spacer .= "";
		
	}
	
?>
<div id="widget_adsync_org_members_container">
	<div id="widget_adsync_org_members">
		<?php echo $org_members_block; ?>
	</div>
</div>
