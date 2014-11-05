<?php
$search_string = elgg_echo('gc_theme:friends:searchname');

$params = array(
	'name' => 'name',
        'id' => 'elgg-input-searchgroup',
	'class' => 'mbm',
        'value' => $search_string,
        'onclick' => "if (this.value=='$search_string') { this.value='' }",
        'onblur' => "if (this.value=='') { this.value='$search_string' };",
);
$form = elgg_view('input/text', $params);
$form .= '<button id="submit" class="button button-icon icon-search group-container-search-button" value="Recherche" type="submit" data-role="none"><span class="button-text">Search</span></button>';
echo $form;
