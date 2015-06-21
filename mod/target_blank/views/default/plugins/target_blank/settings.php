<?php

$plugin = $vars["entity"];

echo "<label>" . elgg_echo("target_blank:settings:link_suffix") . "</label>";
echo elgg_view("input/text", array("name" => "params[link_suffix]", "value" => $plugin->link_suffix));
echo "<div class='elgg-subtext'>" . elgg_echo("target_blank:settings:link_suffix:info") . "</div>";