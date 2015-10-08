<?php
$params = array(
	'name' => 'q',
	'id' => 'elgg-input-searchgroup',
	'class' => 'elgg-input-search mbm',
	'value' => elgg_echo('agora:search:groups'),
);
$form = elgg_view('input/text', $params);
$form .= '<button id="group-find-submit" class="button button-icon icon-search group-container-search-button" value="Recherche" type="submit" data-role="none"><span class="button-text">Search</span></button>';
$form .= elgg_view('input/hidden',array('name'=>'search_type','value'=>'entities'));
$form .= elgg_view('input/hidden',array('name'=>'entity_type','value'=>'group'));
echo $form;
?>
<script>
$(document).ready(function() {
        $('#elgg-input-searchgroup').on('click',function(){
                $('#elgg-input-searchgroup').css('color','#000');
                this.value='';
        });
        $('#group-find-submit').css('pointer-events','none');
        $('#group-find-submit').attr('disabled','disabled');
        $("#elgg-input-searchgroup").live('keyup', function(){
                if ($.trim($("#elgg-input-searchgroup").val())) {
                        $('#group-find-submit .button-text').css('background','url('+elgg.get_site_url()+'mod/agora/views/default/images/icon-search-active.png) no-repeat 0 0');
                        $('#group-find-submit').css('pointer-events','all');
                        $('#group-find-submit').removeAttr('disabled','disabled');
                } else {
                        $('#group-find-submit .button-text').css('background','url('+elgg.get_site_url()+'mod/agora/views/default/images/icon-search-inactive.png) no-repeat 0 0');
                        $('#group-find-submit').css('pointer-events','none');
                        $('#group-find-submit').attr('disabled','disabled');
                };
        });
});
</script>
