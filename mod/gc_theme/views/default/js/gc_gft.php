<?php
?>
elgg.provide('elgg.gc_gft');

/**
 * @param {Number} guid
 * @constructor
 */
elgg.gc_gft.GFT = function (guid) {
	this.guid = guid;
	this.$item = $('#item-river-' + guid);
};

elgg.gc_gft.GFT.prototype = {
	/**
	 * Get a jQuery-wrapped reference to the form
	 *
	 * @returns {jQuery} note: may be empty
	 */
	getForm: function () {
		return $('#elgg-form-gc_gft-save-' + this.guid);

	},

	/**
	 * @param {Function} complete Optional function to run when animation completes
	 */
	hideForm: function (complete) {
		complete = complete || function () {};
		this.getForm().slideUp('fast', complete).data('hidden', 1);
		this.$item.find('.groupforumtopic_edit_area > .elgg-output').show();
	},

	showForm: function () {
		this.$item.find('.groupforumtopic_edit_area > .elgg-output').hide();
		this.getForm().slideDown('medium').data('hidden', 0);
	},

	loadForm: function () {
		var that = this;

		// Get the form using ajax
		elgg.ajax('ajax/view/gc_theme/ajax/edit_gc_gft?guid=' + this.guid, {
			success: function(html) {
				// Add the form to DOM
				//console.log("IN LOADFORM SUCCESS"+JSON.stringify(that));
				that.$item.find('.groupforumtopic_edit_area').append(html);

				that.showForm();

				var $form = that.getForm();

				$form.find('.elgg-button-cancel').on('click', function () {
					that.hideForm();
					return false;
				});

				// save
				$form.on('submit', function () {
					that.submitForm();
					return false;
				});
			}
		});
	},

	submitForm: function () {
		var that = this,
			$form = this.getForm(),
			//value = $form.find('input[name=title]').val();
			value = $form.find('textarea[name=description]').val();

		elgg.action('discussion/save', {
			data: $form.serialize(),
			success: function(json) {
				if (json.status === 0) {
					// Update list item content
					that.$item.find('.groupforumtopic_edit_area > .elgg-output').html(value);
				}
				that.hideForm(function () {
					that.getForm().remove();
				});
			}
		});

		return false;
	},

	toggleEdit: function () {
		var $form = this.getForm();
		if ($form.length) {
			if ($form.data('hidden')) {
				this.showForm();
			} else {
				this.hideForm();
			}
		} else {
			this.loadForm();
		}
		return false;
	}
};

/**
 * Initialize comment inline editing
 * 
 * @return void
 */
elgg.gc_gft.init = function() {
	$(document).on('click', "[id^='groupforumtopic-edit-']", function () {
		// store object as data in the edit link
		guid = this.id.split('-').pop();
		dc = new elgg.gc_gft.GFT(guid);
		$(this).data('GFT', dc);
		dc.toggleEdit();
		return false;
	});
};

elgg.register_hook_handler('init', 'system', elgg.gc_gft.init);
