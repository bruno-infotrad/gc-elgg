<?php
$content = '';
$group_list = elgg_extract('group_list', $vars, '');
foreach($group_list as $group){
	
	$content .= elgg_view('input/checkbox',array('name'=>$group->name,'value'=>$group->guid,'class'=>'contribute-to')).$group->name.'<br>';
}
$content .= elgg_view('output/url', array(
        'text' => elgg_echo('agora:contribute'),
        'href' => '#',
        'class' => 'elgg-button elgg-button-submit form-uneditable-input',
        'id' => 'select-contribute-to',
));
echo $content;
?>
