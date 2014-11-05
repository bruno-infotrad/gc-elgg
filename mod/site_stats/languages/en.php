<?php
/**
 * English language file
 *
 * It is a mapping from a descriptor string to a display string.
 */

$mapping = array(
	'site_stats:stats'									=> "Statistics",
	'site_stats:page_title'								=> "Site statistics",
	'site_stats:section_title'							=> "Site statistics",
		
	'item:user:__base__'								=> "Users",
	'item:group:__base__'								=> "Groups",
	'item:object:groupforumtopic'						=> "Discussion topics",
	'item:object:thewire'								=> "Wire posts",
	'item:object:messages'								=> "Messages",
	'item:object:file'									=> "Files",
	'item:object:page'									=> "Pages",
	'item:object:page_top'								=> "Top-level pages",
	'item:object:bookmarks'								=> "Bookmarks",
	'item:object:poll'									=> "Polls",
	'item:object:blog'									=> "Blogs",

	// event logging
	'site_stats:event_log_msg' => "SITE STATS => %s: '%s, %s' in %s",
	'site_stats:hook_log_msg' => "SITE STATS => %s: '%s, %s' in %s",
		
);

// this is the English mapping
add_translation('en', $mapping);
