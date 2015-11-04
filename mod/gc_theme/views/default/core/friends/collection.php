<?php
/**
 * View a friends collection
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['collection'] The individual friends collection
 */

$coll = $vars['collection'];

if (is_array($vars['collection']->members)) {
	$count = sizeof($vars['collection']->members);
} else {
	$count = 0;
}

echo "<li><h2>";

//as collections are private, check that the logged in user is the owner
if ($coll->owner_guid == elgg_get_logged_in_user_guid()) {
	echo "<div class=\"friends_collections_controls\">";
	echo elgg_view('output/url', array(
			'href' => 'action/friends/collections/delete?collection=' . $coll->id,
			'class' => 'delete_collection',
			'text' => elgg_view_icon('delete'),
			'encode_text' => false,
			'confirm' => true,
		));
	echo "</div>";
}
echo '<div id="gc-collection-rename-'.$coll->id.'">'.$coll->name.'</div>';
echo "<div class='elgg-menu' id='gc-collection-edit-rename-$coll->id'><input id='gc-collection-name-$coll->id' name='name' value ='$coll->name'><a href='#' class='elgg-button elgg-button-cancel mlm' id='gc-collection-cancel-$coll->id'>".elgg_echo('cancel')."</a><a href='#' class='elgg-menu elgg-button gc_theme-submit-button' id='gc-collection-save-$coll->id'>".elgg_echo('submit')."</a></div>";
echo " (<span id=\"friends_membership_count{$vars['friendspicker']}\">{$count}</span>) </h2>";

// individual collection panels
$friends = $vars['collection']->entities;
if ($friends) {
	$content = elgg_view('core/friends/collectiontabs', array(
		'owner' => elgg_get_logged_in_user_entity(),
		'collection' => $vars['collection'],
		'friendspicker' => $vars['friendspicker'],
	));

	echo elgg_view('input/friendspicker', array(
		'entities' => $friends,
		'value' => $vars['collection']->members,
		'content' => $content,
		'replacement' => '',
		'friendspicker' => $vars['friendspicker'],
	));
?>
<?php //@todo JS 1.8: no ?>
	<script type="text/javascript">
	var site_url = elgg.get_site_url();
	$(function () {
		$('#friends-picker_placeholder<?php echo $vars['friendspicker']; ?>').load(elgg.config.wwwroot + 'pages/friends/collections/pickercallback.php?username=<?php echo elgg_get_logged_in_user_entity()->username; ?>&type=list&collection=<?php echo $vars['collection']->id; ?>');
		$("#gc-collection-cancel-<?php echo $coll->id;?>").on('click',function(event) {
			$("#gc-collection-edit-rename-<?php echo $coll->id;?>").hide();
			$("#gc-collection-rename-<?php echo $coll->id;?>").show();
			event.stopPropagation();
		});
		$("#gc-collection-save-<?php echo $coll->id;?>").on('click',function(event) {
			new_name=$('#gc-collection-name-<?php echo $coll->id;?>').val();
			$("#gc-collection-edit-rename-<?php echo $coll->id;?>").hide();
			$("#gc-collection-rename-<?php echo $coll->id;?>").html(new_name);
			$("#gc-collection-rename-<?php echo $coll->id;?>").show();
			$.post(elgg.security.addToken(site_url+'action/friends/collections/rename?&collection_id=' + <?php echo $coll->id;?> + '&name=' + new_name ), function (data) {
				data = $.parseJSON(data);
				if(data.system_messages.success){
                        		elgg.system_message(data.system_messages.success);
                		} else if(data.system_messages.error){
                        		elgg.system_message(data.system_messages.error);
                		}
			});
			event.stopPropagation();
		});
		$("#gc-collection-edit-rename-<?php echo $coll->id;?>").on('click',function(event) {
			event.stopPropagation();
		});
		$("#gc-collection-rename-<?php echo $coll->id;?>").on('click',function(event) {
			$("#gc-collection-edit-rename-<?php echo $coll->id;?>").css('display','inline-block');
			$("#gc-collection-rename-<?php echo $coll->id;?>").hide();
			new_name=$('#gc-collection-name-<?php echo $coll->id;?>').val();
			event.stopPropagation();
		});
/*
		$(document).on('click', function(event) {
			if (!$(event.target).closest('#gc-collection-rename-<?php echo $coll->id;?>').length) {
				console.log('OUT '+new_name);
				$('#gc-collection-rename-<?php echo $coll->id;?>').replaceWith("<div id='gc-collection-rename-<?php echo $coll->id;?>'><?php echo $coll->name;?></div>");
				$.post(elgg.security.addToken(site_url+'action/friends/collections/rename?&collection_id=' + <?php echo $coll->id;?> + '&name=' + group_guid ), function (data) {
					if(data == 'true'){
                        			elgg.system_message(preference_saved);
                			} else {
                        			elgg.system_message(preference_not_saved);
                			}

				});
				event.stopPropagation();
			} else {
				$("#gc-collection-rename-<?php echo $coll->id;?>").replaceWith("<div id='gc-collection-rename-<?php echo $coll->id;?>'><input id='gc-collection-name-<?php echo $coll->id;?>' name='name' value ='<?php echo $coll->name;?>'></div>");
				//$("#gc-collection-rename-<?php echo $coll->id;?>").replaceWith("<div id='gc-collection-rename-<?php echo $coll->id;?>'><input type='text' name='name' value ='<?php echo $coll->name;?>'></div>");
				$('#gc-collection-name-<?php echo $coll->id;?>').focus();
				new_name=$('#gc-collection-name-<?php echo $coll->id;?>').val();
				console.log('IN '+new_name);
				event.stopPropagation();
			}
		});
*/
	});
	</script>
	<?php
}

// close friends-picker div and the accordian list item
echo "</li>";
