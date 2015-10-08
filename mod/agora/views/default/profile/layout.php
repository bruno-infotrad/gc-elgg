<?php
echo elgg_view('profile/user_header');
echo elgg_view_menu('dfait_adsync', array(
        'entity' => elgg_get_page_owner_entity(),
        'class' => 'elgg-menu-hz',
        'sort_by' => 'priority',
));
echo '<div class="elgg-composer '.$class.'">';
echo '<div id="myTabs" class="elgg-profile-menu '.$class.'">';
echo elgg_view_menu('profile', array(
        'entity' => elgg_get_page_owner_entity(),
        'class' => 'elgg-menu-hz tabs',
        'sort_by' => 'priority',
));
echo '</div>';
echo '</div>';
?>
<script>
$('.elgg-profile-menu').tabs({
        spinner: '',
        panelTemplate: '<div><div class="elgg-ajax-loader"></div></div>'
});
$('#myTabs').tabs().simpleScrollableTab();
</script>
