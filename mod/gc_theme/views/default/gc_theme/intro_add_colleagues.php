<?php
$content = elgg_view_form("friends/multi_invite", array(
                "id" => "multi_invite",
                "class" => "elgg-form-alt mtm",
                "enctype" => "multipart/form-data"
        ), array(
                "entity" => elgg_get_logged_in_user_entity(),
        ));

echo $content;
