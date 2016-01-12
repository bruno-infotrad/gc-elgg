<?php 
	$q = sanitize_string(get_input("q"));
	$limit = (int) get_input("limit", 50);
	$name = get_input("name","tags");
	$result = array();
	if(!empty($q)){
		$params['limit'] = $limit;
		$params['tag_names'] = array($name);
		//Need this test because interest metadata is also used in user profile :-(
		if ($name == 'interests') {
			$params['type'] = 'group';
		}
		$params['count'] = FALSE;
		$params['wheres'] = array("msv.string like '%$q%'");
		$returned_tags = elgg_get_tags($params);
		foreach($returned_tags as $returned_tag){
			$tag = $returned_tag->tag;
			if (!preg_match('/"|;/',$tag)) {
				$result[] = array("content" => $returned_tag->tag);
			}
		}
	}
	header("Content-Type: application/json");
	echo json_encode(array_values($result));
	exit();
