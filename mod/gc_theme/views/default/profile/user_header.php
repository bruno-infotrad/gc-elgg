<?php
$user = elgg_get_page_owner_entity();
$logged_in_username = elgg_get_logged_in_user_entity()->username;
if (isset($vars['class'])) {
	$class = "$class {$vars['class']}";
}
if (elgg_get_context() == 'profile') {
	if($_SESSION['language'] == "fr") {
		$gctitle_value = $user->title_french;
		$gcorg_value = $user->org_label_fr;
	} else {
		$gctitle_value = $user->title_english;
		$gcorg_value = $user->org_label_en;
	}
	$gcaddress_value = $user->building_address;
	$briefdescription = $user->briefdescription;
?>
<div id="profile-info-cont" class="detail-info-cont">
	<div class="profile-icon-block">
	<?php echo elgg_view_entity_icon($user, 'medium');?>
	<?php if ($user->username == $logged_in_username) {
		$button_block = '<div id="profile-modify-block"><h4>';
		$button_block .=  elgg_echo('gc_theme:profile:modify');
		$button_block .=  '</h4>';
		$button_block .= elgg_view_menu('title', array('sort_by' => 'priority'));
		$button_block .=  '</div>';
	      } else {
		$button_block .= elgg_view_menu('title', array('sort_by' => 'priority'));
	      }
	      echo $button_block;
	?>
	</div>
	<div class="data-cont profile-data-cont">
		<h2><?php echo $user->name; ?></h2>
		<div class="group-meta">
       			<p><?php echo $gctitle_value;?></p>
       			<p><?php echo $gcorg_value;?></p>
       			<p><?php echo $gcaddress_value;?></p>
       			<p>&nbsp;</p>
       			<p id='gc_theme-brief-description'><?php echo $briefdescription;?></p>
		</div>
		<div class="counter-cont">
			<p><?php //echo elgg_view('output/url', array( 'text' => '<strong>'.$group->getMembers(0, 0, TRUE).'</strong> '.elgg_echo('groups:member'), 'value' => '#'));?></p>
		</div>
	</div>
</div>
<?php } ?>
