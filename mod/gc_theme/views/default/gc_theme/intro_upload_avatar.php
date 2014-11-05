<?php
$entity = elgg_get_logged_in_user_entity();
$content = elgg_view('core/avatar/upload', array('entity' => $entity));
echo "<div id='gc_theme-intro-uploadavatar'>".$content."</div>";
