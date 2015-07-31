define(function(require) {
	var elgg = require('elgg');
	var $ = require('jquery'); require('jquery.ckeditor');
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
				//editable.on( 'change', function( event ) {
					var edata = e.getData();
					//var texte = editable.getText();
					//var donnees = editable.getData();
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
