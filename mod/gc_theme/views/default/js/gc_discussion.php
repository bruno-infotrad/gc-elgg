<?php
?>
elgg.provide('elgg.gc_discussion');

/**
 * @param {Number} guid
 * @constructor
 */
elgg.gc_discussion.Discussion = function (guid) {
	this.guid = guid;
	this.$item = $('#elgg-object-' + guid);
};

elgg.gc_discussion.Discussion.prototype = {
	/**
	 * Get a jQuery-wrapped reference to the form
	 *
	 * @returns {jQuery} note: may be empty
	 */
	gc_getForm: function () {
		//console.log("IN GETFORM "+this.guid);
		return $('#add-discussion-reply-' + this.guid);
	},

	/**
	 * @param {Function} complete Optional function to run when animation completes
	 */
	gc_hideForm: function (complete) {
		//console.log("IN HIDEFORM");
		complete = complete || function () {};
		this.gc_getForm().slideUp('fast', complete).data('hidden', 1);
	},

	gc_showForm: function () {
		//console.log("IN SHOWFORM");
		this.gc_getForm().slideDown('medium').data('hidden', 0);
	},

	gc_loadForm: function () {
		//console.log("IN LOADFORM");
		var that = this;

		// Get the form using ajax
		elgg.ajax('ajax/view/gc_theme/ajax/add_discussion_reply?guid=' + this.guid, {
			success: function(html) {
				//console.log("IN LOADFORM SUCCESS"+JSON.stringify(that));
				// Add the form to DOM
				$('#'+that.guid).closest('.elgg-body').append(html);

				that.gc_showForm();

				var $form = that.gc_getForm();

				$form.find('.elgg-button-cancel').on('click', function () {
					that.gc_hideForm();
					return false;
				});

				// save
				$form.on('submit', function () {
					that.gc_submitForm();
					return false;
				});
			}
		});
	},

	gc_submitForm: function () {
		var that = this,
			$form = this.gc_getForm();
			//value = $form.find('textarea[name=generic_comment]').val();

		elgg.action('discussion/reply/save', {
			data: $form.serialize(),
			success: function(json) {
				//console.log("COMMENT SAVED "+JSON.stringify(json));
				if (json.status === 0) {
					// Update list item content
					elgg.ajax('ajax/view/gc_theme/ajax/view/discussion_reply?guid=' + json.output, {
						success: function(html) {
							//console.log("COMMENT GRABBED "+that.guid);
							if ($('.elgg-river-responses-'+that.guid).length) {
								//console.log("COMMENT WITH RESPONSE");
								$('ul.elgg-list .elgg-river-comments-'+that.guid).append(html);
								that.gc_hideForm(function () {
									that.gc_getForm().remove();
								});
							} else {
								//console.log("COMMENT WITHOUT RESPONSE");
								html = "<div class=\"elgg-river-responses-"+that.guid+"\"><ul class=\"elgg-list elgg-river-comments-"+that.guid+"\">"+html+"</ul></div>";
								//console.log("COMMENT WITHOUT RESPONSE "+html);
								that.gc_getForm().replaceWith(html);
							}
						}
					});
				}
			}
		});

		return false;
	},

	gc_toggleEdit: function () {
		//console.log("IN TOGGLEEDIT");
		var $form = this.gc_getForm();
		if ($form.length) {
			//console.log("IN NON ZERO FORM LENGTH");
			if ($form.data('hidden')) {
				//console.log("IN HIDDEN FORM");
				this.gc_showForm();
			} else {
				//console.log("IN NON HIDDEN FORM");
				this.gc_hideForm();
			}
		} else {
			//console.log("IN ZERO FORM LENGTH");
			this.gc_loadForm();
		}
		return false;
	}
};

/**
 * Initialize comment inline editing
 * 
 * @return void
 */
elgg.gc_discussion.init = function() {
	$(document).on('click', 'a.elgg-discussion-reply-add', function () {
			//console.log('FIRED '+$(this).attr('id'));
			var guid = $(this).attr('id');
			// store object as data in the edit link
			dc = new elgg.gc_discussion.Discussion(guid);
			$(this).data('Discussion', dc);
			dc.gc_toggleEdit();
			return false;
	});
};

elgg.register_hook_handler('init', 'system', elgg.gc_discussion.init);
