<?php
/**
 * French language file
 *
 * It is a mapping from a descriptor string to a display string.
 */

$mapping = array(
	'site_stats:stats'									=> "Statistiques",
	'site_stats:page_title'								=> "Statistiques du site",
	'site_stats:section_title'							=> "Statistiques du site",
		
	'item:user:__base__'								=> "Usagers",
	'item:group:__base__'								=> "Groupes",
	'item:object:groupforumtopic'						=> "Sujets de discussion",
	'item:object:thewire'								=> "Microblogues",
	'item:object:messages'								=> "Messages",
	'item:object:file'									=> "Fichiers",
	'item:object:page'									=> "Pages",
	'item:object:page_top'								=> "Pages de plus haut niveau",
	'item:object:bookmarks'								=> "Signets",
	'item:object:poll'									=> "Sondages",
	'item:object:blog'									=> "Blogues",

	// event logging
	'site_stats:event_log_msg' => "SITE STATS => %s: '%s, %s' dans %s",
	'site_stats:hook_log_msg' => "SITE STATS => %s: '%s, %s' dans %s",
		
);

add_translation('fr', $mapping);
