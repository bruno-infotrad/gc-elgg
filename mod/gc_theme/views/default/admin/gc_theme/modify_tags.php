<?php 
$oldtag = get_input("oldtag");
$newtags = get_input("newtags");
$input_guids = get_input("guids");
if (! $oldtag || ! $newtags) {
	$error = elgg_echo('gc_theme:manage_tags:missing_tags');
}
if (!$error) {
	if ($input_guids) {
		if (preg_match("/,/",$input_guids)) {
			$guids = split(',',$input_guids);
		} else {
			$guids = array($input_guids);
		}
	} else {
		$error = elgg_echo('gc_theme:manage_tags:missing_guids');
	}
	if (!$error) {
		foreach ($guids as $guid) {
			$entity = get_entity($guid);
			if ($entity) {
				$existing_tags = $entity->tags;
				//elgg_log("MANAGE TAGS existing tags".var_export($existing_tags,true),'NOTICE');
				$index = array_search($oldtag, $existing_tags);
				if ( $index !== false ) {
					unset( $existing_tags[$index] );
				}
				//elgg_log("MANAGE TAGS existing tags".var_export($existing_tags,true),'NOTICE');
				$input_tags = string_to_tag_array($newtags);
				//elgg_log("MANAGE TAGS input tags".var_export($input_tags,true),'NOTICE');
				$updated_tags = array_merge($existing_tags,$input_tags);
				//elgg_log("MANAGE TAGS updated tags".var_export($updated_tags,true),'NOTICE');
				$entity->tags = $updated_tags;
				if (! $entity->save()) {
					$error = elgg_echo('gc_theme:manage_tags:entity_save_error',array($guid));
				}
			}
		}
	}
}
$status = $error ? $error : 'success';
echo $status;
exit();
