<?php
if (elgg_is_logged_in()) {
	$site_url = elgg_get_site_url();
	$help_url = "http://www.elgg.org";
	$help_label = elgg_echo("agora:help");
	$contact_label = elgg_echo("agora:contact");
	$contact_url = "mailto:ti@infotrad.ca";
	$about_label = elgg_echo("agora:about");
	$about_url = "http://intra/collaboration/agora/index.aspx?lang=$langue";
	$training_label = elgg_echo('agora:sidebar_link:training');
	$training_url = "http://community.elgg.org";
	$content =<<<_HTML
<nav id="secondary-nav">
            <h2>AGORA</h2>
            <ul>
                <li><a href="$help_url">$help_label</a></li>
                <li><a href="$contact_url" style="background-image:none!important;">$contact_label</a></li>
                <li><a href="$training_url">$training_label</a></li>
            </ul>
        </nav>
_HTML;
	echo $content;
}
?>
