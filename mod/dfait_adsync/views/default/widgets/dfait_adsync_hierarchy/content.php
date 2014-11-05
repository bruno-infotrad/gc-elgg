<?php
 
	$hierarchy_block = "";

	$hierarchy = $vars['hierarchy'];
	
	$spacer = "";
	foreach ($hierarchy as $person) {

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
		
		$hierarchy_block .= $person_block;
		$spacer .= "&nbsp;";
		
	}
	
?>
<div id="widget_adsync_hierarchy_container">
	<div id="widget_adsync_hierarchy">
		<?php echo $hierarchy_block; ?>
	</div>
</div>
