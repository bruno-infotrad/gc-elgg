<?php
if ($_SESSION['language'] == 'fr') {
	include(elgg_get_plugins_path()."gc_theme/views/default/gc_theme/header_fr.php");
} else {
	include(elgg_get_plugins_path()."gc_theme/views/default/gc_theme/header_en.php");
}
