<?php
if ($_SESSION['language'] == 'fr') {
	include(elgg_get_plugins_path()."gc_theme/views/default/gc_theme/footer_fr.php");
} else {
	include(elgg_get_plugins_path()."gc_theme/views/default/gc_theme/footer_en.php");
}
