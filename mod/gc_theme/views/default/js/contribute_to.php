<?php
?>
elgg.provide('elgg.contribute_to');

elgg.contribute_to.init = function() {
	$('body').on('click','.elgg-form-gc-theme-contribute-to', function() {
		if ($('.elgg-form-gc-theme-contribute-to input[type=checkbox]').is(':checked')){
			$('#select-contribute-to').css('opacity','1.0');
		} else {
			$('#select-contribute-to').css('opacity','0.5');
		};
	});
	$('body').on('click','#select-contribute-to', function() {
		var container_id = [];
		var container_ids;
		$(':checkbox:checked').each(function(i){
          		container_id[i] = $(this).val();
			if (container_ids) {
				container_ids = container_ids+','+container_id[i];
			} else {
				container_ids = container_id[i];
			}
        	});
		if ($("#thewire-textarea").val() && container_ids) {
			$(".elgg-foot>input[type=hidden]").val(function (i,v){
				return container_ids;
			});
			$('#thewire-submit-button').trigger('click');
			setTimeout(function(){
				$.fancybox.close();
                	},3000);
		}
        });
};
elgg.register_hook_handler('init', 'system', elgg.contribute_to.init);
