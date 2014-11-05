<?php

$items_to_include = array( 
		"item:user:__base__" 			=> NIL,
		"item:group:__base__"			=> NIL,
		"item:object:groupforumtopic" 	=> NIL,  
		"item:object:thewire" 			=> NIL,
		"item:object:messages" 			=> NIL, 
		"item:object:file" 				=> NIL,  
		"item:object:page" 				=> NIL,  
		"item:object:page_top" 			=> NIL,  
		"item:object:bookmarks"			=> NIL,  
		"item:object:poll" 				=> NIL,
// 		"item:object:poll_choice" 		=> NIL,  
		"item:object:blog" 				=> NIL,  
	);

// Get entity statistics
$entity_stats = get_entity_statistics();

foreach ($entity_stats as $k => $entry) {
	elgg_log("k [$k]", 'DEBUG');
// 	arsort($entry);
	foreach ($entry as $a => $b) {
		elgg_log("  a [$a], b [$b]", 'DEBUG');
		
		if (array_key_exists("item:$k:$a", $items_to_include)) {
			$items_to_include["item:$k:$a"] = $b;
			elgg_log("  including item:k:a [item:$k:$a]", 'DEBUG');
		} else {
			elgg_log("  skipping item:k:a [item:$k:$a]", 'DEBUG');
		}
	}
}
		
$title = elgg_echo("site_stats:page_title");
$section_heading = elgg_echo("site_stats:section_title");

$content = <<< END
	<div class="elgg-head"><h3>{$section_heading}</h3></div>
	<table class="elgg-table-alt">
END;

$even_odd = "";

foreach ($items_to_include as $k => $v) {
	elgg_log("k [$k], v [$v]", 'DEBUG');

	$k = elgg_echo($k);
	
	//This function controls the alternating class
	$even_odd = ( 'odd' != $even_odd ) ? 'odd' : 'even';
	
	$content .= <<< END
		<tr class="{$even_odd}">
			<td>{$k}:</td>
			<td>{$v}</td>
		</tr>
	
END;
}


	$content .= "</table>";
	
	$vars = array(
			'content' => $content,
	);
	$body = elgg_view_layout('one_sidebar', $vars);
	echo elgg_view_page($title, $body);	
?>

