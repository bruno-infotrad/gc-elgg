<?php
$search_string = elgg_echo('members:searchname');

$params = array(
        'name' => 'name',
        'id' => 'elgg-input-searchgroup',
        'class' => 'mbm',
        'value' => $search_string,
);
$form = elgg_view('input/text', $params);
$form .= '<button id="member-search-submit" class="button button-icon icon-search group-container-search-button" value="Recherche" type="submit" data-role="none"><span class="button-text">Search</span></button>';
echo $form;
?>
<script>
$(document).ready(function() {
        $('#elgg-input-searchgroup').on('click',function(){
		$('#elgg-input-searchgroup').css('color','#000');
		this.value='';
        });
        $('#member-search-submit').css('pointer-events','none');
        $('#member-search-submit').attr('disabled','disabled');
        $("#elgg-input-searchgroup").live('keyup', function(){
		if ($.trim($("#elgg-input-searchgroup").val())) {
                        $('#member-search-submit .button-text').css('background','url('+elgg.get_site_url()+'mod/gc_theme/views/default/images/icon-search-active.png) no-repeat 0 0');
                        $('#member-search-submit').css('pointer-events','all');
                        $('#member-search-submit').removeAttr('disabled','disabled');
                } else {
                        $('#member-search-submit .button-text').css('background','url('+elgg.get_site_url()+'mod/gc_theme/views/default/images/icon-search-inactive.png) no-repeat 0 0');
                        $('#member-search-submit').css('pointer-events','none');
                        $('#member-search-submit').attr('disabled','disabled');
                };
        });
});
</script>
