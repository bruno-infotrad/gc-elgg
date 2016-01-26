<?php 
$tags = get_input("tags");
$input_guids = get_input("guids");
if (! $tags) {
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
		$error = 'Missing guids';
		$error = elgg_echo('gc_theme:manage_tags:missing_guids');
	}
	if (!$error) {
		foreach ($guids as $guid) {
			$entity = get_entity($guid);
			if ($entity) {
				$entity->tags = string_to_tag_array($tags);
				if (! $entity->save()) {
					$error = 'Error saving entity '. $guid;
					$error = elgg_echo('gc_theme:manage_tags:entity_save_error');
				}
			}
		}
	}
}
$status = $error ? $error : 'success';
echo $status;
exit();
