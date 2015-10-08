<?php
$text = $vars['value'];
$text = preg_replace( '/(^|[^\w])([\w\-\.]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})/i', '$1<a href="mailto:$2@$3">$2@$3</a>', $text);
echo $text;
