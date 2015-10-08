<?php
if (elgg_is_logged_in()) {
$site_url = elgg_get_site_url();
if ($_SESSION['language'] == "fr") {
	$langue = "fra";
	$help_url = "${site_url}pages/view/17898/aidefaq";

} else {
	$langue = "eng";
	$help_url = "${site_url}pages/view/17546/help-faqs";
}
$help_label = elgg_echo("agora:help");
$contact_label = elgg_echo("agora:contact");
$contact_url = "${site_url}pages/view/19301/contact-us-contacteznous";
$about_label = elgg_echo("agora:about");
$about_url = "http://intra/collaboration/agora/index.aspx?lang=$langue";
$stats_label = elgg_echo("site_stats:stats");
$stats_url = "${site_url}site_stats";
$training_label = elgg_echo('agora:sidebar_link:training');
$training_url = "http://intra/collaboration/agora/index.aspx?lang=$langue#training";
$content =<<<_HTML
<nav id="secondary-nav">
            <h2>AGORA</h2>
            <ul>
                <li><a href="$help_url">$help_label</a></li>
                <li><a href="$contact_url">$contact_label</a></li>
                <li><a href="$about_url">$about_label</a></li>
                <li><a href="$stats_url">$stats_label</a></li>
                <li><a href="$training_url">$training_label</a></li>
            </ul>
        </nav>
_HTML;
echo $content;
}
?>
