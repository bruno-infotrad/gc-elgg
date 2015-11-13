<?php 
	$q = sanitize_string(get_input("q"));
	$limit = (int) get_input("limit", 50);
	$result = array();
	if(!empty($q)){
		$params['type'] = 'group';
		$params['limit'] = $limit;
		$params['tag_names'] = array('interests');
		$params['count'] = FALSE;
		$params['wheres'] = array("msv.string like '%$q%'");
		$group_tags = elgg_get_tags($params);
		foreach($group_tags as $group_tag){
			$tag = $group_tag->tag;
			if (!preg_match('/"|;/',$tag)) {
				$result[] = array("content" => $group_tag->tag);
			}
		}
	}
	header("Content-Type: application/json");
	echo json_encode(array_values($result));
	exit();
