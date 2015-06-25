<?php
$event_guid = $vars['event_guid'];
$content = elgg_get_entities_from_relationship(array(
	'type' => 'group',
	'relationship' => 'member',
	'relationship_guid' => elgg_get_logged_in_user_guid(),
	'inverse_relationship' => false,
	'limit' => 0,
	));
if (!$content) {
	$content = elgg_echo('groups:none');
}
echo elgg_view_form('gc_theme/notify_groups', array(), array('group_list' => $content, 'event_guid' => $event_guid ));
//Load script after because fancybox is just an overlay, not a window
?>
<script>
$('#select-notify-groups').css('pointer-events','none');
$('#select-notify-groups').css('opacity','0.5');
$('body').on('click','.elgg-form-gc-theme-notify-groups', function() {
	if ($('.elgg-form-gc-theme-notify-groups input[type=checkbox]').is(':checked')){
		$('#select-notify-groups').css('opacity','1.0');
		$('#select-notify-groups').removeAttr('disabled');
		$('#select-notify-groups').css('pointer-events','all');
		$('input#select-notify-groups:disabled').val(false);
	} else {
		$('#select-notify-groups').css('opacity','0.5');
		$('#select-notify-groups').attr('disabled','disabled');
		$('#select-notify-groups').css('pointer-events','none');
	};
});
$('#select-notify-groups').on('click', function() {
	event_guid = $(this).attr("rel");
	var container_id = [];
	var container_ids;
	$(':checkbox:checked').each(function(i){
		container_id[i] = $(this).val();
		if (container_ids) {
			container_ids = container_ids+','+container_id[i];
		} else {
			container_ids = container_id[i];
		}
	});
	if (container_ids && event_guid) {
		$("#container-guid").val(function (i,v){ return container_ids; });
		setTimeout(function(){ $.colorbox.close(); },3000);
	}
});
</script>
