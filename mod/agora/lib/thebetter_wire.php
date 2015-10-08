<?php
/**
 * Replace urls, hash tags, and @'s by links
 *
 * @param string $text The text of a post
 * @return string
 */
function thebetterwire_filter($text) {
        $text = ' ' . $text;

        // email addresses
        $text = preg_replace(
                                '/(^|[^\w])([\w\-\.]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})/i',
                                '$1<a href="mailto:$2@$3">$2@$3</a>',
                                $text);

        // links
        $text = parse_urls($text);

        // usernames
	if(preg_match_all('/@([\w]+)/',$text,$matches)) {
		foreach ($matches[1] as $match) {
			if (get_user_by_username($match)){
        			$text = preg_replace( "/@$match/", '<a href="' . elgg_get_config('wwwroot') . 'profile/'.$match.'">@'.$match.'</a>&nbsp;', $text);
			}
		}
	}

        // hashtags
        $text = preg_replace(
                                '/(^|[^\w])#(\w*[^\s\d!-\/:-@]+\w*)/',
                                '$1<a href="' . elgg_get_config('wwwroot') . 'thewire/tag/$2">#$2</a>',
                                $text);

        $text = trim($text);

        return $text;
}
?>
