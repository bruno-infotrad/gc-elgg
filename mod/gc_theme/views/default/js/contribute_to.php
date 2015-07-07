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
				$.colorbox.close();
                	},3000);
		}
        });

	$('#thewire-contribute-to').attr('disabled','disabled');
	$('#thewire-contribute-to').css('pointer-events','none');
	$("[id$=thewire-textarea]").live('keyup', function(){
		if ($.trim($("[id$=thewire-textarea]").val())) {
			if (!$('input#thewire-exec-content').is(':checked')) {
				$('#thewire-contribute-to').css('opacity','1.0');
				$('#thewire-contribute-to').css('pointer-events','all');
				$('#thewire-contribute-to').removeAttr('disabled');
			};
			$('input#thewire-submit-button:disabled').val(false);
			$('#thewire-submit-button').css('opacity','1.0');
		} else {
			$('#thewire-contribute-to').css('opacity','0.5');
			$('#thewire-contribute-to').css('pointer-events','none');
			$('#thewire-contribute-to').attr('disabled','disabled');
			$('input#thewire-submit-button.elgg-button.elgg-button-submit:disabled').val(true);
			$('#thewire-submit-button').css('opacity','0.5');
		};
	});

	$('input#thewire-exec-content').change(function () {
		if ($.trim($("[id$=thewire-textarea]").val())) {
			if (!$('input#thewire-exec-content').is(':checked')) {
				$('#thewire-contribute-to').css('opacity','1.0');
				$('#thewire-contribute-to').css('pointer-events','all');
				$('#thewire-contribute-to').removeAttr('disabled');
			} else {
				$('#thewire-contribute-to').css('opacity','0.5');
				$('#thewire-contribute-to').css('pointer-events','none');
				$('#thewire-contribute-to').attr('disabled','disabled');
			};
		};
	});

	$("#thewire-textarea").on('focus', function() {
		elgg.user_handle('#thewire-textarea');
	});
/*
	CKEDITOR.on('instanceReady', function(event) {
		for ( var i in CKEDITOR.instances ){
			var oEditor   = CKEDITOR.instances[i];
			var id   = $(oEditor).attr('id');
			//console.log("i="+i+" id="+id);
			//elgg.user_handle('#thewire-textarea');
			elgg.user_handle(i);
		}
	});
*/
	CKEDITOR.on('instanceReady', function(event) {
		var e = CKEDITOR.instances['thewire-textarea']
		var editable = e.editable();

		var exec_content_warning = "<?php echo elgg_echo('gc_theme:exec_content:warning');?>";
		$('input#thewire-exec-content').live('click',function(event){
			if ($('input#thewire-exec-content').is(':checked')) {
				if (confirm(exec_content_warning) == false) {
					event.preventDefault();
					return;
				}
			}
		});

		editable.on( 'keyup', function( event ) {
			//alert( e.getData() );
			if (e.getData()) {
				if (!$('input#thewire-exec-content').is(':checked')) {
					$('#thewire-contribute-to').css('opacity','1.0');
					$('#thewire-contribute-to').css('pointer-events','all');
					$('#thewire-contribute-to').removeAttr('disabled');
				};
				$('#thewire-submit-button').removeAttr('disabled');
				//$('input#thewire-submit-button:disabled').val(false);
				$('#thewire-submit-button').css('opacity','1.0');
			} else {
				$('#thewire-contribute-to').css('opacity','0.5');
				$('#thewire-contribute-to').css('pointer-events','none');
				$('#thewire-contribute-to').attr('disabled','disabled');
				//$('input#thewire-submit-button.elgg-button.elgg-button-submit:disabled').val(true);
				$('#thewire-submit-button').attr('disabled','disabled');
				$('#thewire-submit-button').css('opacity','0.5');
			};
		});

		$('.elgg-form-compound-add').on('submit', function (e){
			var $form = $(this);
			if ($form.data('submitted') === true) {
				e.preventDefault();
			} else {
				$form.data('submitted',true);
			}
		});
	});
};

elgg.register_hook_handler('init', 'system', elgg.contribute_to.init);
