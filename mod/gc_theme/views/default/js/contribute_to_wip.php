<?php
?>
elgg.provide('elgg.contribute_to');

elgg.contribute_to.init = function() {
//LEGACY PIECE FOR PLAINTEXT EDITOR
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

//NEW PIECE FOR CKEDITOR
//Windows load to ensure EDITOR api is available
	$( window ).load(function() {
/*
 * Comment out for now
		CKEDITOR.on('instanceReady', function(event) {
			for ( var i in CKEDITOR.instances ){
				var oEditor   = CKEDITOR.instances[i];
				var id   = $(oEditor).attr('id');
				console.log("i="+i+" id="+id);
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
				var texte = e.getData();
				if (texte) {
					$('#thewire-textarea').html(texte);
					elgg.user_handle('thewire-textarea');
					console.log("DATA"+texte);
					if (!$('input#thewire-exec-content').is(':checked')) {
						$('#thewire-contribute-to').css('opacity','1.0');
						$('#thewire-contribute-to').css('pointer-events','all');
						$('#thewire-contribute-to').removeAttr('disabled');
					};
					$('#thewire-submit-button').removeAttr('disabled');
					//$('input#thewire-submit-button:disabled').val(false);
					$('#thewire-submit-button').css('opacity','1.0');
/*
 * Comment out for now
					// Now user handle helper
					
        				var data;
        				var destination = $(e).attr('id')+'_bottom';
					//console.log("DESTINATION="+destination);
					//var editeur = $('#thewire-textarea').ckeditor().editor;
					//$(".cke_wysiwyg_frame").contents().each(function(){
					//$(".cke_wysiwyg_frame").contents().find('body').data(function(){
					//$(".cke_wysiwyg_frame").contents().find('body').html(function(){
					$("thewire-textarea").each(function(){
						console.log("IN EACH"+JSON.stringify(this));
						$(this)
						// don't navigate away from the field on tab when selecting an item
						.bind( "keydown", function( event ) {
							console.log("IN BIND");
							if ( event.keyCode === $.ui.keyCode.TAB &&
									$( this ).data( "autocomplete" ).menu.active ) {
								event.preventDefault();
							}
						})
						.autocomplete({
							source: function( request, response ) {
								console.log("IN AUTOCOMPLETE");
								var usagers = request.term.match(/^@\w+|\s+@\w+/g);
								var usager = usagers[usagers.length-1].replace(/@/,'');
								var cible = elgg.get_site_url()+"user_autocomplete";
								jquery_string = "\"#"+destination+" input[name='<?php echo $name; ?>[]']\"";
								$.getJSON( cible, {
									q: usager,
									'user_guids': function() {
										var result = "";
										$(jquery_string).each(function(index, elem){
											if(result == ""){
												result = $(this).val();
											} else {
												result += "," + $(this).val();
											}
										});
					
										return result;
									}
								}, response );
							},
							search: function() {
								console.log("IN AUTOCOMPLETE SEARCH "+JSON.stringify(this));
								// minLength = 2
								//var term = this.value.match(/^@\w{3,}|\s+@\w{3,}/g);
								var term = this.term.match(/^@\w{3,}|\s+@\w{3,}/g);
								if (term === null){
									return false;
								} else {
									var term2 = this.match(/^@\w+\W+|\s+@\w+\W+/g);
									//var term2 = this.value.match(/^@\w+\W+|\s+@\w+\W+/g);
									//var term2 = this.value.match(/^@\w+\s+|\s+@\w+\s+/g);
									if (term2 !== null && term2.length == term.length){
										return false;
									} else {
										return true;
									}
								}
							},
							focus: function() {
								// prevent value inserted on focus
								return false;
							},
							select: function( event, ui ) {
								//Mean regexp for multiline support
								this.value = this.value.replace(/(.*)(\W?)@\w+$/g,"$1$2@"+ui.item.value);
								return false;
							},
							autoFocus: true
						}).data("ui-autocomplete")._renderItem = function( ul, item ) {
							console.log("IN RENDERITEM");
							var list_body = "";
							list_body = item.content;
							
						
							return $( "<li></li>" )
							.data( "item.autocomplete", item )
							.append( "<a>" + list_body + "</a>" )
							.appendTo( ul );
						};
					});
*/
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
	});
};

elgg.register_hook_handler('init', 'system', elgg.contribute_to.init);
