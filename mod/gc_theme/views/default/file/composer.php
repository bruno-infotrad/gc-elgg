<?php
elgg_load_library('elgg:file');
$form_vars = array(
	'enctype' => 'multipart/form-data', 
);
$body_vars = file_prepare_form_vars();
//hack! Elgg engine should take care of this, or blog/save form should be coded better
if (elgg_is_xhr() && isset($vars['container_guid'])) {
        elgg_set_page_owner_guid($vars['container_guid']);
}
echo elgg_view_form('file/upload', $form_vars, array_merge($body_vars, $vars));
