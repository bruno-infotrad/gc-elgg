<?php
/**
 * Elgg user display
 *
 * @uses $vars['entity'] ElggUser entity
 * @uses $vars['size']   Size of the icon
 */

$entity = $vars['entity'];
$size = elgg_extract('size', $vars, 'tiny');

$icon = elgg_view_entity_icon($entity, $size, $vars);

// Simple XFN
$rel = '';
if (elgg_get_logged_in_user_guid() == $entity->guid) {
	$rel = 'rel="me"';
} elseif (check_entity_relationship(elgg_get_logged_in_user_guid(), 'friend', $entity->guid)) {
	$rel = 'rel="friend"';
}

$title = "<a href=\"" . $entity->getUrl() . "\" $rel>" . $entity->name . "</a>";

//Hack to remove metadata on followers page since I can't seem to filter at the source
if(elgg_get_context() == 'friendsof') {
	if ($_SESSION['language'] == "fr") {
		$subtitle = $entity->title_french;
	} else {
		$subtitle = $entity->title_english;
	}
	$post_button = elgg_view('output/url',array('href' => "/messages/compose?send_to=".$entity->getGUID(),
                                        'text' => elgg_echo('messages:sendmessage'),
					'id' => 'message-friendsof',
                                        'class' => 'elgg-button elgg-button-action elgg-button-submit form-uneditable-input',));
} else {
	$subtitle = $entity->briefdescription;
	$metadata = elgg_view_menu('entity', array(
		'entity' => $entity,
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
	));
}


if (elgg_in_context('owner_block') || elgg_in_context('widgets')) {
	$metadata = '';
}

$context =  elgg_get_context();
if ($context == 'gallery') {
	echo $icon;
	echo '<div class="data-cont">'.$entity->name.'</div>';
} elseif ($context == 'livesearch') {
	echo '<div class="elgg-image-block">';
	echo '<div class="elgg-image">'.$icon.'</div>';
	echo '<div class="elgg-body"><h3>'.$entity->name.'</h3></div>';
	echo '</div>';
} else {
	if ($entity->isBanned()) {
		$banned = elgg_echo('banned');
		$params = array(
			'entity' => $entity,
			'title' => $title,
			'metadata' => $metadata,
			'post_button' => $post_button,
		);
	} else {
		$params = array(
			'entity' => $entity,
			'title' => $title,
			'metadata' => $metadata,
			'post_button' => $post_button,
			'subtitle' => $subtitle,
			'content' => elgg_view('user/status', array('entity' => $entity)),
		);
	}

	$list_body = elgg_view('user/elements/summary', $params);

	echo elgg_view_image_block($icon, $list_body, $vars);
}
