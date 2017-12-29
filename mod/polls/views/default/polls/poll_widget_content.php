<?php
elgg_load_library('elgg:polls');

$poll = elgg_extract('entity', $vars);
if (! $poll->title) {
	$poll->title = $poll->question;
}
if($msg = elgg_extract('msg', $vars)) {
	echo '<p>'.$msg.'</p>';
}

if (elgg_is_logged_in()) {
	$user_guid = elgg_get_logged_in_user_guid();
	$can_vote = !polls_check_for_previous_vote($poll, $user_guid);
	
	//if user has voted, show the results
	if ($poll->owner_guid == $user_guid) {
		$results_display = "block";
		$poll_display = "block";
		$show_text = elgg_echo('polls:show_poll');
	} elseif ($poll->status == 'closed') {
		$results_display = "block";
		$poll_display = "none";
		$show_text = elgg_echo('polls:closed');
		$closed_text = elgg_echo("polls:closedlongtext");
		$can_vote = FALSE;
	} elseif (!$can_vote) {
		$results_display = "block";
		$poll_display = "none";
		$show_text = elgg_echo('polls:show_poll');
		$voted_text = elgg_echo("polls:voted");
	} else {
		$results_display = "none";
		$poll_display = "block";
		$show_text = elgg_echo('polls:show_results');
	}
} else {
	$results_display = "block";
	$poll_display = "none";
	$show_text = elgg_echo('polls:show_poll');
	$voted_text = elgg_echo('polls:login');
	$can_vote = FALSE;
}
?>
<?php echo '<h4>'.$poll->title.'</h4><p>'.$poll->question.'</p>';?>
<div id="poll-post-body-<?php echo $poll->guid; ?>" class="poll_post_body" style="display:<?php echo $results_display ?>;">
<?php if (!$can_vote) {echo '<p>'.$voted_text.'</p>';}?>
<?php if ($poll->status == 'closed') {echo '<p>'.$closed_text.'</p>';}?>
<?php echo elgg_view('polls/results_for_widget', array('entity' => $poll)); ?>
</div>
<?php
$page_owner = $poll->getContainerEntity();
if ($page_owner->readonly != 'yes') {
	echo elgg_view_form('polls/vote', array('id'=>'poll-vote-form-'.$poll->guid),array('entity' => $poll,'callback'=>1,'form_display'=>$poll_display));
}
?>
