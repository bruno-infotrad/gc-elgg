<?php
/**
 * Group search form
 *
 * @uses $vars['entity'] ElggGroup
 */

$params = array(
	'name' => 'q',
	'id' => 'elgg-input-searchgroup',
	'class' => 'elgg-input-search mbm',
	'value' => $tag_string,
);
$form = elgg_view('input/text', $params);
//echo elgg_view('input/submit', array('id'=>'group-container-search-button','value' => elgg_echo('search:go')));
$form .= '<button id="group-search-submit" class="button button-icon icon-search group-container-search-button" value="Recherche" type="submit" data-role="none"><span class="button-text">Search</span></button>';
//$form .= elgg_view('input/hidden',array('name'=>'search_type','value'=>'entities'));
$form .= elgg_view('input/checkbox',array('name'=>'entity_type','value'=>'user', 'default' => false));
$form .= elgg_echo('members');
$form .= elgg_view('input/hidden', array(
	'name' => 'container_guid',
	'value' => $vars['entity']->getGUID(),
));
echo $form;
?>
<script>
$(document).ready(function() {
        $('#elgg-input-searchgroup').on('click',function(){
                $('#elgg-input-searchgroup').css('color','#000');
                this.value='';
        });
        $('#group-search-submit').css('pointer-events','none');
        $('#group-search-submit').attr('disabled','disabled');
        $("#elgg-input-searchgroup").live('keyup', function(){
                if ($.trim($("#elgg-input-searchgroup").val())) {
                        $('#group-search-submit .button-text').css('background','url('+elgg.get_site_url()+'mod/agora/views/default/images/icon-search-active.png) no-repeat 0 0');
                        $('#group-search-submit').css('pointer-events','all');
                        $('#group-search-submit').removeAttr('disabled','disabled');
                } else {
                        $('#group-search-submit .button-text').css('background','url('+elgg.get_site_url()+'mod/agora/views/default/images/icon-search-inactive.png) no-repeat 0 0');
                        $('#group-search-submit').css('pointer-events','none');
                        $('#group-search-submit').attr('disabled','disabled');
                };
        });
});
</script>
