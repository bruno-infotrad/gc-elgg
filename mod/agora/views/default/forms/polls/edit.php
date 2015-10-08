<?php
$poll = elgg_extract('entity', $vars);
if ($poll) {
	$guid = $poll->guid;
} else  {
	$guid = 0;
}

$question = $vars['fd']['question'];
$tags = $vars['fd']['tags'];
$access_id = $vars['fd']['access_id'];
$poll_status = $vars['fd']['status'];
$title = $vars['fd']['title'];
if ($guid) {
	$title = $poll->title;
	if (! $title) {
	//legacy poll with no title, do not restrict length
		$title = $question;
	} else {
		$maxlength = 70;
	}
} else {
	$maxlength = 70;
}

$title_label = elgg_echo('polls:title');
$title_textbox = elgg_view('input/text', array('class'=>'poll-title','name' => 'title', 'value' => $title,'maxlength'=>$maxlength));

$question_label = elgg_echo('polls:question');
$question_textbox = elgg_view('input/text', array('name' => 'question', 'value' => $question));

$responses_label = elgg_echo('polls:responses');
$responses_control = elgg_view('polls/input/choices',array('poll'=>$poll));

$tag_label = elgg_echo('tags');
$tag_input = elgg_view('input/tags', array('class'=>'poll-tag','name' => 'tags', 'value' => $tags));

$access_label = elgg_echo('access');
$access_input = elgg_view('input/access', array('name' => 'access_id', 'value' => $access_id));

$status_label = elgg_echo('polls:settings:status');
$status_input = elgg_view('input/dropdown', array(
                        'name' => 'poll_status',
                        'value' => $poll_status,
                        'options_values' => array(
                                'open' => elgg_echo('polls:open'),
                                'closed' => elgg_echo('polls:closed'),
                        ),
                ));


$submit_input = elgg_view('input/submit', array( 'class'=>'gc-input-file-1em','name' => 'submit', 'id'=>'poll-submit-button','value' => elgg_echo('save')));
$submit_input .= ' '.elgg_view('input/button', array('class'=>'gc-input-file-browse','name' => 'cancel', 'id' => 'polls_edit_cancel', 'type'=> 'button', 'value' => elgg_echo('cancel')));

$poll_front_page = elgg_get_plugin_setting('front_page','polls');

if(elgg_is_admin_logged_in() && ($poll_front_page == 'yes')) {
	$front_page_input = '<p>';
	if ($vars['fd']['front_page']) {
		$front_page_input .= elgg_view('input/checkbox',array('name'=>'front_page','value'=>1,'checked'=>'checked'));
	} else {
		$front_page_input .= elgg_view('input/checkbox',array('name'=>'front_page','value'=>1));
	}
	$front_page_input .= elgg_echo('polls:front_page_label');
	$front_page_input .= '</p>';
} else {
	$front_page_input = '';
}

if (isset($vars['entity'])) {
	$entity_hidden = elgg_view('input/hidden', array('name' => 'guid', 'value' => $guid));
} else {
	$entity_hidden = '';
}

$entity_hidden .= elgg_view('input/hidden', array('name' => 'container_guid', 'value' => elgg_get_page_owner_guid()));

echo <<<__HTML
		<p>
			<label class='poll-title-cont'>$title_label</label>$title_textbox
		</p>
		<p>
			<label>$question_label</label>
			$question_textbox
		</p>
		<p>
			<label>$responses_label</label>
			$responses_control
		</p>
		<p>
			<label>$tag_label</label>
			$tag_input
		</p>
		$front_page_input
		<p>
			<label>$access_label</label>
			$access_input
			<label>$status_label</label>
			$status_input
		</p>
		<p>
		$entity_hidden
		$submit_input
		</p>
__HTML;

		// TODO - move this JS
		?>
<div></div>
<script type="text/javascript">
$('#polls_edit_cancel').click(
	function() {
		window.location.href="<?php echo elgg_get_site_url(); ?>";
	}
);
</script>
