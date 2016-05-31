<?php
if (elgg_is_logged_in()) {
$site_url = elgg_get_site_url();
if ($_SESSION['language'] == "fr") {
	$langue = "fra";
	$training = "formation";
	$help_url = "${site_url}pages/view/17898/aidefaq";

} else {
	$langue = "eng";
	$training = "training";
	$help_url = "${site_url}pages/view/17546/help-faqs";
}
$help_label = elgg_echo("gc_theme:help");
$contact_label = elgg_echo("gc_theme:contact");
$contact_url = "${site_url}pages/view/19301/contact-us-contacteznous";
$about_label = elgg_echo("gc_theme:about");
$about_url = " http://modus/gi_ti-im_it/gi_ti-im_it/7633993-7369136.aspx?lang=$langue";
$stats_label = elgg_echo("site_stats:stats");
$stats_url = "${site_url}site_stats";
$training_label = elgg_echo('gc_theme:sidebar_link:training');
$training_url = "http://modus/gi_ti-im_it/gi_ti-im_it/7633993-7369136.aspx?lang=$langue#$training";
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
