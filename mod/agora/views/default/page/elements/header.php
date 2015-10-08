<?php
if ($_SESSION['language'] == 'fr') {
	include(elgg_get_plugins_path()."agora/views/default/agora/header_fr.php");
} else {
	include(elgg_get_plugins_path()."agora/views/default/agora/header_en.php");
}
