<?php
if ($_SESSION['language'] == 'fr') {
	include(elgg_get_plugins_path()."gc_theme/views/default/gc_theme/footer_fr.php");
} else {
	include(elgg_get_plugins_path()."gc_theme/views/default/gc_theme/footer_en.php");
}
?>
<script>
$(document).ready(function(){
	if (window.location.href.match(/\/59163\//)){
		window.location.replace("https://gcconnex.gc.ca/groups/profile/34520708/englobal-affairs-canada-choir-without-bordersfrchorale-sans-frontiu00e8res-daffaires-mondiales-canada");
	} else if (window.location.href.match(/\/181546\//)){
		window.location.replace("https://gcconnex.gc.ca/groups/profile/34575612/englobal-affairs-canada-u2013-sii-cornerfraffaires-mondiales-canada-u2013-coin-sii");
	}
});
</script>
