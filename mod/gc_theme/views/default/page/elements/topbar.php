<?php
$site_url = elgg_get_site_url();
$username = elgg_get_logged_in_user_entity()->username;
$advanced = elgg_echo('gc_theme:advanced');
$feedback = elgg_echo('gc_theme:feedback');
$feedback_url = "${site_url}groups/profile/17712/tell-us-how-we-can-improve-agora-ditesnous-comment-amliorer-agora";
$object_label = elgg_echo('gc_theme:object');
$all_label = elgg_echo('gc_theme:all_objects');
$blog_label = elgg_echo('blog:blogs');
$page_label = elgg_echo('pages');
$file_label = elgg_echo('files');
$wire_label = elgg_echo('thewire');
$topic_label = elgg_echo('groups:topic');
$poll_label = elgg_echo('polls');
$all_agora_label = elgg_echo('gc_theme:agora_all');
$groups_agora_label = elgg_echo('gc_theme:agora_groups');
$users_agora_label = elgg_echo('gc_theme:agora_users');
$contributed_by_label = elgg_echo('gc_theme:contributed_by');
$approximate_date_label = elgg_echo('gc_theme:approximate_date');
$approximate_date = '2010-01-01';
$search_label = elgg_echo('search:go');
$security_tokens = elgg_view('input/securitytoken');
$language = ($_SESSION['language'] == 'fr')?'EN':'FR';
elgg_load_js('elgg.new_messages');
elgg_load_js('elgg.contributed_by');
$hide_advanced = '';
// Horrible hack to remove advanced search for MSIE and Firefox
if ((preg_match('/MSIE/',$_SERVER['HTTP_USER_AGENT']))||(preg_match('/rv:11/',$_SERVER['HTTP_USER_AGENT']))||(preg_match('/Firefox/',$_SERVER['HTTP_USER_AGENT']))) {
	$hide_advanced = "style='display: none'";
}
echo <<<_HTML
<div id="gcwu-bnr" role="banner">
	<div id="gcwu-bnr-in">
		<div id="gcwu-title"><p id="gcwu-title-in"><a href="${site_url}dashboard">AGORA</a></p></div>
_HTML;
		if (elgg_is_logged_in()) {
			$home=elgg_echo('home');
			$messages=elgg_echo('messages');
			$submit=elgg_echo('search:go');
echo <<<_HTML
		<div class="button-cont site-actions">
			<a class="button button-icon icon-messages with-badge" href="${site_url}messages/inbox/$username"><span class="button-text">$messages</span><span class="gc-messages-new"></span></a>
		</div>
		<div id="site-search" role="search">
			<h2>Search</h2>
			<form action="${site_url}search" method="get" class="search-form">
				<label for="srch">Search website</label>
				<input id="srch" name="q" type="search" value="" size="20"  maxlength="150"/>
				<details class="toggle-menu">
					<summary id="advanced-options" class="button" href="#">$advanced</summary>
					<div class="advanced-search-cont toggle-menu-body" $hide_advanced>
						<label for="search-section">$object_label</label>
						<select id="search-section" name="entity_subtype">
							<option value=>$all_label</option>
							<option value="blog">$blog_label</option>
							<option value="page_top">$page_label</option>
							<option value="file">$file_label</option>
							<option value="thewire">$wire_label</option>
							<option value="groupforumtopic">$topic_label</option>
							<option value="poll">$poll_label</option>
						</select>
						<label for="search-section">Section</label>
						<select id="search-section" name="advanced_type">
							<option value=>$all_agora_label</option>
							<option value="groups_content">$groups_agora_label</option>
							<option value="user">$users_agora_label</option>
						</select>
						<label for="search-contrib-by">$contributed_by_label</label>
						<input id="search-contrib-by" name="contributed_by" type="text" onfocus="elgg.contributed_by('search-contrib-by')"/>
						<div id="search-contrib-by-autocomplete_results"></div>
						<label for="search-date">$approximate_date_label</label>
						<input id="search-date" name="approximate_date" type="text" value=$approximate_date />
						<button class="button button-small button-accent" id= 'advanced-search-submit-button'>$search_label</button>
					</div>
				</details>
				<button id="search-submit-button" class="button button-icon icon-search" value="$submit" type="submit"><span class="button-text">Search</span></button>
			</form>
		</div>
		<div class="button-cont site-settings">
			<a class="button button-icon icon-settings" href="${site_url}settings/user/$username"><span class="button-text">Settings</span></a>
			<a class="button" href="$feedback_url">$feedback</a>
		</div>
_HTML;
		}
?>
	</div>
</div>
<script>
$(document).ready(function() {
	var approximate_date_value = '<?php echo $approximate_date; ?>';
	$('#search-date').on('click',function(){
		if (this.value==approximate_date_value) {
			$('#search-date').css('color','#000');
			this.value='';
		}
	});
	$('.button.button-small.button-accent').on('click',function(){
		if ($('#search-date').val() == '2010-01-01') {$('#search-date').val('');}
	});
	$('.button.button-icon.icon-search').on('click',function(){
		if ($('#search-date').val() == '2010-01-01') {$('#search-date').val('');}
	});

	$('#search-submit-button').css('pointer-events','none');
	$('#search-submit-button').attr('disabled','disabled');
	$('#advanced-search-submit-button').css('opacity','0.5');
	$('#advanced-search-submit-button').css('pointer-events','none');
	$('#advanced-search-submit-button').attr('disabled','disabled');
	$("#srch").live('keyup', function(){
	        if ($.trim($("#srch").val())) {
			$('#search-submit-button .button-text').css('background','url(mod/gc_theme/views/default/images/icon-search-active.png) no-repeat 0 0');
			$('#search-submit-button').css('pointer-events','all');
			$('#search-submit-button').removeAttr('disabled','disabled');
			$('#advanced-search-submit-button').css('opacity','1.0');
			$('#advanced-search-submit-button').css('pointer-events','all');
			$('#advanced-search-submit-button').removeAttr('disabled','disabled');
	        } else {
			$('#search-submit-button').css('pointer-events','none');
			$('#search-submit-button .button-text').css('background','url(mod/gc_theme/views/default/images/icon-search-inactive.png) no-repeat 0 0');
			$('#search-submit-button').attr('disabled','disabled');
			$('#advanced-search-submit-button').css('opacity','0.5');
			$('#advanced-search-submit-button').css('pointer-events','none');
			$('#advanced-search-submit-button').attr('disabled','disabled');
	        };
	});
});
</script>
