<?php
/**
 * Elgg ajax edit comment form
 *
 * @package Elgg
 * @subpackage Core
 */

$guid = get_input('guid');
$base_url = get_input('base_url');
$options['base_url'] = $base_url;

$entity = get_entity($guid);
elgg_set_page_owner_guid($entity->getContainerGUID());

/*
if (!elgg_instanceof($comment, 'object', 'comment') || !$comment->canEdit()) {
	return false;
}
*/
$form_vars = array(
	'class' => 'hidden mvl',
	'id' => "add-comment-{$guid}",
);
$body_vars = array('entity' => $entity);
echo '<div>'.elgg_view_form('comment/save', $form_vars, $body_vars).'</div>';
