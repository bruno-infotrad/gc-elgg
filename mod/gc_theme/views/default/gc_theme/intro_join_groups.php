<?php
$content = elgg_view_form("groups/intro_join_groups", array(
                "id" => "intro-join-group",
                "class" => "elgg-form-alt mtm",
        ), array(
                "entity" => elgg_get_logged_in_user_entity(),
        ));

echo $content;
