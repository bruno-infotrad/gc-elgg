<?php
function elgg_view_agora_icon($name, $class = '') {
        if ($class === true) {
                $class = 'float';
        }
        return "<span class=\"elgg-agora-icon elgg-agora-icon-$name $class\"></span>";
}
