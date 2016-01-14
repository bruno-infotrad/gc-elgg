<?php
//show hide compound
?>
elgg.provide('elgg.toggle_compound');
elgg.toggle_compound.init = function() {
	setTimeout(function(){
		$(".elgg-compound").slideUp(5000);
	},5000);
	$(".elgg-menu-compound").hover(function(){
		$(".elgg-compound").slideDown(1000);
	});
	$(".elgg-compound").hover(function(){
		$(".elgg-compound").slideDown(1000);
	},function(){
		$(".elgg-compound").slideUp(1000);
	});
	
}
elgg.register_hook_handler('init', 'system', elgg.toggle_compound.init);
