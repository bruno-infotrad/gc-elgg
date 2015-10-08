<?php 
/**
 * Elgg secondary sidebar contents
 *
 * You can override, extend, or pass content to it
 *
 * @uses $vars['sidebar_alt] HTML content for the alternate sidebar
 */

if (elgg_is_active_plugin('polls')) {
	elgg_load_js('elgg.polls');
	$options = array( 'type' => 'object', 'subtype' => 'poll', 'metadata_name_value_pairs' => array(array('name'=>'front_page','value'=>1)), 'limit' => 1,);
	$polls = elgg_get_entities_from_metadata($options);
	if ($polls) {
		$vars['entity'] = get_entity($polls[0]->guid);
		$GLOBALS['GC_THEME']->debug('BRUNO POLLS '.$vars['entity']->question);
		$sidebar_alt = '<h3>'.$vars['entity']->question.'</h3>';
		$sidebar_alt .= '<div class="clearfloat"></div><div id="poll-container-'.$vars['entity']->guid.'"class="poll_post">';
        	$sidebar_alt .=elgg_view('polls/poll_widget_content',$vars);
		$sidebar_alt .= '</div>';
		echo $sidebar_alt;
	}
}
