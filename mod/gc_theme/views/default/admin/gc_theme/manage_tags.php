<?php
$body = '<div class="gc-theme-manage-tags">';
$body .= '<div class="gc-theme-search-tags">';
$search_string = get_input('tags');
$body .= '<h4>'.elgg_echo('gc_theme:modify_tags:search').'</h4><br>';
$body .=  elgg_view_form('gc_theme/admin/manage_tags',array('action' => '/admin/gc_theme/manage_tags', 'method' => 'get','class' => 'gc-theme-search-tag'),array('name' => 'tags' , 'value' => $search_string));
$body .= '</div>';
if ($search_string) {
	$results = elgg_trigger_plugin_hook('search', "tags", array('query' => $search_string,'exact' => 'yes', 'limit' => 0, 'type' => ELGG_ENTITIES_ANY_VALUE), NULL);
	$results_count = $results['count'];
	if ($results_count > 0){
		$body .= '<hr />';
		$body .= '<div class="gc-theme-modify-tags">';
		$body .= '<h4>'.elgg_echo('gc_theme:modify_tags:howto').'</h4><br>';
		if ($results_count == 1){
			$body .= '<h4>'.elgg_echo('gc_theme:entity_to_be_modified',array($results['count'])).'</h4>';
		} else {
			$body .= '<h4>'.elgg_echo('gc_theme:entities_to_be_modified',array($results['count'])).'</h4>';
		}
                $params['tag_names'] = array('tags','interests');
		$params['wheres'] = array("msv.string = '$search_string'");
                $returned_tags = elgg_get_tags($params);
		//echo "RETURNED TAGS ".var_export($returned_tags,true).' '.elgg_get_metastring_id($search_string);
		$entities = $results['entities'];
		foreach ($entities as $entity) {
			$guids = ($guids)? ($guids.','.$entity->getGUID()) : $entity->getGUID();
		}
		//echo $guids;
		$body .= elgg_view("input/hidden", array('class' => 'old-tags', 'name' => 'oldtag', 'value' => $search_string));
		$body .= elgg_view("input/text", array('class' => 'new-tags', 'name' => 'newtags', 'value' => $search_string));
		$body .= elgg_view("input/hidden", array('name' => 'guids', 'value' => $guids));
		$body .= elgg_view("input/button", array('class' => 'modify-tags', "value" => elgg_echo("modify")));
		//echo var_export($results,true);
		$body .= elgg_view('search/list',array('results' => $results, 'exact' => 'yes'));
		$body .= '</div>';
	}
}
$body .= '</div>';
echo $body;
?>
<script>
if (elgg.is_admin_logged_in()) {
	var site_url = elgg.get_site_url();
	var tags_modified = "<?php echo elgg_echo('gc_theme:tags_modified'); ?>";
	var old_tag  = encodeURIComponent("<?php echo $search_string ;?>");
	var new_tags = '';
	$(".new-tags").on('change', function() {
		new_tags = encodeURIComponent($(this).val());
	});
	var guids = "<?php echo $guids;?>";
	$('.elgg-button.modify-tags').on('click', function() {
		$.post(elgg.security.addToken(site_url+'admin/gc_theme/modify_tags?oldtag='+old_tag+'&newtags=' + new_tags + '&guids=' + guids)).success(function(status) {
			if (status == "success") {
				elgg.system_message(tags_modified);
			} else {
				elgg.register_error(status);
			}
		}).fail(function() {;
			elgg.system_message(failed);
		});
	});
}
</script>

