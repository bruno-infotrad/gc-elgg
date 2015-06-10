<script>
var site_url = elgg.get_site_url();
var user_guid = elgg.get_logged_in_user_guid();
var preference_saved = "<?php echo elgg_echo('groups:joined'); ?>";
$('body').on('click','.js-post-tell-us', function() {
                        $.post(elgg.security.addToken(site_url+'action/groups/join?group_guid=17712&&user_guid=' + user_guid + '&js-post=true'));
                        elgg.system_message(preference_saved);
});
$('body').on('click','.js-post-voice-up', function() {
                        $.post(elgg.security.addToken(site_url+'action/groups/join?group_guid=47238&&user_guid=' + user_guid + '&js-post=true'));
                        elgg.system_message(preference_saved);
});
</script>
<?php
$site_url = elgg_get_site_url();
$user = elgg_get_logged_in_user_entity();
$content = "<div class='gc_theme-intro'>";
$content .= elgg_echo('gc_theme:intro');
$content .= elgg_echo('gc_theme:intro:add_colleagues');
$content .= elgg_view('output/url', array(
			'text' => elgg_echo('gc_theme:intro:add_colleagues:title'),
			'href' => $site_url.'intro_add_colleagues',
			'class' => 'elgg-lightbox elgg-button elgg-button-submit elgg-button-fancybox',
			'id' => 'gc_theme-intro-addcolleagues',
			));
$content .= elgg_echo('gc_theme:intro:add_colleagues2');
//Groups
$content .= elgg_echo('gc_theme:intro:join_groups');
$tell_us_group = get_entity(17712);
//$voice_up_group = get_entity(47238);
$voice_up_group = get_entity(13866);
if ( $tell_us_group ||  $voice_up_group) {
	if (! $tell_us_group->isMember($user) || ! $voice_up_group->isMember($user)) {
		$content .= elgg_echo('gc_theme:intro:join_groups2');
		$content .= '<ul>';
		if ( $tell_us_group) {
			if (! $tell_us_group->isMember($user)) {
				$actions_url = elgg_echo('groups:join');
			        $join_button = "<div class='elgg-button elgg-button-action elgg-button-join js-post-tell-us'>$actions_url</div>";
				$content .= "<li id='intro-tell-us-join'>".elgg_view('output/url',array('text' => elgg_echo('gc_theme:intro:tell_us'),'href'=>$tell_us_group->getURL(),'is_trusted' => true,)).' '.$join_button.'</li>';
			}
		}
		if ( $voice_up_group) {
			if (! $voice_up_group->isMember($user)) {
				$actions_url = elgg_echo('groups:join');
			        $join_button = "<div class='elgg-button elgg-button-action elgg-button-join js-post-voice-up'>$actions_url</div>";
				$content .= '<li>'.elgg_view('output/url',array('text' => elgg_echo('gc_theme:intro:voice_up'),'href'=>$voice_up_group->getURL(),'is_trusted' => true,)).' '.$join_button.'</li>';
			}
		}
		$content .= '</ul>';
	}
}
$content .= elgg_echo('gc_theme:intro:join_groups3');
$content .= '<p>'.elgg_view('output/url', array(
			'text' => elgg_echo('gc_theme:intro:join_group:title'),
			'href' => $site_url.'intro_join_groups',
			'class' => 'elgg-lightbox elgg-button elgg-button-submit elgg-button-fancybox',
			'id' => 'gc_theme-intro-joingroup',
			)).'</p>';
//Profile
$content .= elgg_echo('gc_theme:intro:profile_setup');
$content .= '<p>'.elgg_view('output/url', array(
			'text' => elgg_echo('gc_theme:intro:upload_avatar:title'),
			'href' => $site_url.'intro_upload_avatar',
			'class' => 'elgg-lightbox elgg-button elgg-button-submit elgg-button-fancybox',
			'id' => 'gc_theme-intro-uploadavatar-button',
			)).'</p>';
$profile_url = "<a href=\"${site_url}profile/$user->username\">".elgg_echo('profile')."</a>";
$content .= elgg_echo('gc_theme:intro:profile_setup2',array($profile_url));
//Videos
$content .= elgg_echo('gc_theme:intro:training_videos');
$content .= elgg_echo('gc_theme:intro:training_videos2');
$content .= "</div>";
echo $content;
