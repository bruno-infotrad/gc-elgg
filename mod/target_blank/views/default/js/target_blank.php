<?php
/**
 * JS to open links in a new window.
 */
?>
//<script>
elgg.provide('elgg.target_blank');

elgg.target_blank.init = function() {
	var selector = 'a[href^="http://"]:not([target], [href^="<?php echo elgg_get_site_url();?>"]),'
				   + 'a[href^="https://"]:not([target], [href^="<?php echo elgg_get_site_url();?>"])';

	$external_links = $(selector);
	$external_links.live("click", function(){
		$(this).attr("target", "_blank");
	}).addClass("target-blank");

	<?php 
	$suffix = elgg_get_plugin_setting("link_suffix", "target_blank");
	if (!empty($suffix)) {
		?>
		$external_links.each(function() {
			if (!$(this).find(" > img").length) {
				$(this).append("<?php echo $suffix; ?>");
			}
		});
		<?php 
	}
	?>
}

elgg.register_hook_handler('init', 'system', elgg.target_blank.init);
