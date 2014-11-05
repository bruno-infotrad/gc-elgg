<?php
$form_vars = array(
        'enctype' => 'multipart/form-data',
        'class' => 'elgg-form-embed',
);
echo elgg_view_form('compound/add', $form_vars, $vars);
