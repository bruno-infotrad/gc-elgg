<?php
/*
						//} else {
							//file_thumbnail = '<?php get_entity(;?>file_guid<?php )->getIconURL("tiny");?>';
*/
$extensions_settings = elgg_get_plugin_setting('allowed_extensions', 'file_tools');
?>
define(function(require) {
	var elgg = require('elgg');
	var $ = require('jquery'); require('jquery.ckeditor');
	var allowed_extensions_settings = "<?php echo $extensions_settings;?>";
	//alert(allowed_extensions_settings);
	var CKEDITOR = require('ckeditor');
	
	CKEDITOR.plugins.addExternal('mediaembed', elgg.get_site_url() + 'mod/ckeditor_extended/vendors/plugins/mediaembed/', 'plugin.js');
	
	var elggCKEditor = {

		/**
		 * Toggles the CKEditor
		 *
		 * @param {Object} event
		 * @return void
		 */
		toggleEditor: function(event) {
			event.preventDefault();
	
			var target = $(this).attr('href');
	
			if (!$(target).data('ckeditorInstance')) {
				$(target).ckeditor(elggCKEditor.init, elggCKEditor.config);
				$(this).html(elgg.echo('ckeditor:html'));
			} else {
				$(target).ckeditorGet().destroy();
				$(this).html(elgg.echo('ckeditor:visual'));
			}
		},

		/**
		 * Initializes the ckeditor module
		 *
		 * @return void
		 */
		init: function() {
		},

		/**
		 * CKEditor has decided using width and height as attributes on images isn't
		 * kosher and puts that in the style. This adds those back as attributes.
		 * This is from this patch: http://dev.ckeditor.com/attachment/ticket/5024/5024_5.patch
		 * 
		 * @param {Object} event
		 * @return void
		 */
		fixImageAttributes: function(event) {
			event.editor.dataProcessor.htmlFilter.addRules({
				elements: {
					img: function(element) {
						var style = element.attributes.style;
						if (style) {
							var match = /(?:^|\s)width\s*:\s*(\d+)px/i.exec(style);
							var width = match && match[1];
							if (width) {
								element.attributes.width = width;
							}
							match = /(?:^|\s)height\s*:\s*(\d+)px/i.exec(style);
							var height = match && match[1];
							if (height) {
								element.attributes.height = height;
							}
						}
					}
				}
			});
		},

		/**
		 * CKEditor configuration
		 *
		 * You can find configuration information here:
		 * http://docs.ckeditor.com/#!/api/CKEDITOR.config
		 */
		config: require('elgg/ckeditor/config')

	};

	CKEDITOR.on('instanceReady', elggCKEditor.fixImageAttributes);
	CKEDITOR.on('instanceReady', function(event) {
		if ($.browser.msie) {
	        $('iframe.cke_wysiwyg_frame', event.editor.container.$).contents().on('click', function() {
	        	event.editor.focus();
	        });
		}


		var e = CKEDITOR.instances['thewire-textarea']
		// Test for wire textarea as the button do not exist outside of it
		if (e) {
			var editable = e.editable();

			var exec_content_warning = elgg.echo('gc_theme:exec_content:warning');
			$('input#thewire-exec-content').live('click',function(event){
				if ($('input#thewire-exec-content').is(':checked')) {
					if (confirm(exec_content_warning) == false) {
						event.preventDefault();
						return;
					}
				}
			});

			e.on( 'change', function( event ) {
				var edata = e.getData();
				//console.log("EDONNEES "+edata+" LONGUEUR DES EDONNEES "+edata.length+" DONNEES "+donnees+" LONGUEUR DES DONNEES "+donnees.length+" TEXTE "+texte+" LONGUEUR DU TEXTE "+texte.length);
				if (edata.length != 0) {
					//$('#thewire-textarea').html(texte);
					//elgg.user_handle('thewire-textarea');
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

			e.on( 'paste', function( event ) {
				console.log(event);
				if (event.data.method != 'drop') {
					return;
				}
				var fichier = event.data.dataTransfer.getFile(0);
				//console.log(fichier);
				var type_fichier = fichier.type.split("/")[1];
				//if ( CKEDITOR.fileTools.isTypeSupported( fichier, /image\/(png|jpeg|gif)/ ) ) {
				if ((type_fichier == 'vnd.ms-powerpoint') ||
				    (type_fichier == 'vnd.ms-powerpoint.presentation.macroenabled.12') ||
				    (type_fichier == 'vnd.openxmlformats-officedocument.presentationml.presentation')) {
					type_fichier = 'pptx';
					icon = 'ppt';
				} else if ((type_fichier == 'avi') ||
					   (type_fichier == 'mov') ||
					   (type_fichier == 'mp4')) {
					  icon='video';
				} else if ((type_fichier == 'msword') ||
					   (type_fichier == 'vnd.openxmlformats-officedocument.wordprocessingml.document') ||
					   (type_fichier == 'vnd.openxmlformats-officedocument.wordprocessingml.template')) {
					  type_fichier = 'doc';
					  icon='word';
				} else if ((type_fichier == 'vnd.ms-excel') ||
					   (type_fichier == 'vnd.openxmlformats-officedocument.spreadsheetml.sheet')) {
					  type_fichier = 'xls';
					  icon='excel';
				} else if ((type_fichier == 'mp3') ||
					   (type_fichier == 'midi')) {
					  type_fichier = 'mp3';
					  icon='music';
				} else if (type_fichier == 'pdf') {
					  type_fichier = 'pdf';
					  icon='pdf';
				} else if (type_fichier == 'plain') {
					  type_fichier = 'txt';
					  icon='text';
				} else {
					icon='general';
				}
				if ( allowed_extensions_settings.indexOf(type_fichier) != -1 ) {
					var loader = e.uploadRepository.create( fichier );
					loader.on( 'update', function() {
						$('#ckeditor_file_loader').html('<font color="green">'+loader.status+'</font>');
					} );
					loader.on( 'error', function() {
						$('#ckeditor_file_loader').html('<font color="red">'+loader.message+'</font>');
					} );
					var site_url = elgg.get_site_url();
					var uploadURL = site_url+'action/file/upload2';
					var __elgg_token = $( "input[name='__elgg_token']" )[1].value;
					//console.log('__elgg_token'+__elgg_token);
					var __elgg_ts = $( "input[name='__elgg_ts']" )[1].value;
					//console.log('__elgg_ts'+__elgg_ts);
					var container_guid = $( "input[name='container_guid']" )[1].value;
					//console.log('container_guid'+container_guid);
					var access0 = $( "select[name='access_id']" )[0];
					var access1 = $( "select[name='access_id']" )[1];
					var access_id;
					if (access1) {
						access_id = $( "select[name='access_id']" )[1].value;
					} else if (access0) {
						access_id = $( "select[name='access_id']" )[0].value;
					} else {
						access_id = '2';
					}
					//console.log('access_id'+access_id);

					//console.log('container_guid='+container_guid);
					var fullURL = uploadURL+'?__elgg_token='+__elgg_token+'&__elgg_ts='+__elgg_ts+'&container_guid='+container_guid+'&access_id='+access_id;
					loader.loadAndUpload( fullURL );
					loader.on( 'uploaded', function() {
						var local_file_url = loader.url;
						var file_parts = local_file_url.split("/");
						var file_guid = file_parts[2];
						var file_name = file_parts[3];
						var file_url = site_url+loader.url;
						var file_thumbnail;
						if (type_fichier == 'png' || type_fichier == 'jpeg' || type_fichier == 'gif') {
							file_thumbnail = site_url+'mod/file/thumbnail.php?file_guid='+file_guid+'&size=large';
						} else {
							file_thumbnail = site_url+'mod/gc_theme/graphics/icons/'+icon+'.gif';
						}
http://10.20.28.150/elgg-1.10.5/mod/gc_theme/graphics/icons/pdf
						var dom_element = '<a href="'+file_url+'" class="embed-insert"><img class="elgg-photo " src="'+file_thumbnail+'" alt="'+file_name+'"></a>';
						var element = CKEDITOR.dom.element.createFromHtml( dom_element);
						e.insertElement( element );
						$('#ckeditor_file_loader').empty();
					});
				} else {
					alert("File type is not allowed");
				}
			});

			$('.elgg-form-compound-add').on('submit', function (e){
				var $form = $(this);
				if ($form.data('submitted') === true) {
					e.preventDefault();
				} else {
					$form.data('submitted',true);
				}
			});
		}
    }); 
	// Live handlers don't need to wait for domReady and only need to be registered once.
	$('.ckeditor-toggle-editor').live('click', elggCKEditor.toggleEditor);

	return elggCKEditor;
});
