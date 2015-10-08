/**
 *  JQuery more comments
 *   */

elgg.provide('elgg.toggle_cla');
elgg.toggle_cla = function(){
	$("[class^='elgg-agora-icon control-toggle-cla-']").live('click', function (e) {
		e.preventDefault();
		$(this).toggleClass('control-toggle-cla-on control-toggle-cla-off');
		$('.hide-cla').toggle();
	});
};
elgg.register_hook_handler('init', 'system', elgg.toggle_cla);
