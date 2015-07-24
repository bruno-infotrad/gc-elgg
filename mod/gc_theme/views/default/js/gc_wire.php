<?php
?>
elgg.provide('elgg.gc_wire');

/**
 * @param {Number} guid
 * @constructor
 */
elgg.gc_wire.theWire = function (guid,guid_string) {
	this.guid = guid;
	this.guid_string = guid_string;
	this.$item = $('[id^=item-river-'+guid+']');
	//this.$item = $('#item-river-' + guid);
};

elgg.gc_wire.theWire.prototype = {
	/**
	 * Get a jQuery-wrapped reference to the form
	 *
	 * @returns {jQuery} note: may be empty
	 */
	getForm: function () {
		//return $('#elgg-form-gc_wire-save-' + this.guid);
		return $('[id^=elgg-form-gc_wire-save-'+this.guid+']');

	},

	/**
	 * @param {Function} complete Optional function to run when animation completes
	 */
	hideForm: function (complete) {
		complete = complete || function () {};
		this.getForm().slideUp('fast', complete).data('hidden', 1);
		this.$item.find('.thewire_edit_area > .elgg-output').show();
	},

	showForm: function () {
		this.$item.find('.thewire_edit_area > .elgg-output').hide();
		this.getForm().slideDown('medium').data('hidden', 0);
	},

	loadForm: function () {
		var that = this;

		// Get the form using ajax
		elgg.ajax('ajax/view/gc_theme/ajax/edit_gc_wire?guid='+this.guid+'&guid_string='+this.guid_string, {
			success: function(html) {
				// Add the form to DOM
				//console.log("IN LOADFORM SUCCESS"+JSON.stringify(that));
				that.$item.find('.thewire_edit_area').append(html);

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
			value = $form.find('textarea[name=gc_wire]').val();

		elgg.action('compound/add', {
			data: $form.serialize(),
			success: function(json) {
				if (json.status === 0) {
					// Update list item content
					that.$item.find('.thewire_edit_area > .elgg-output').html(value);
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
elgg.gc_wire.init = function() {
	$(document).on('click', "[id^='wire-edit-']", function () {
		// store object as data in the edit link
		guid_string = this.id.split('-').pop();
		//console.log('GUID_STRING='+guid_string);
		guids = guid_string.split('_');
		//console.log('GUIDS='+JSON.stringify(guids));
		dc = new elgg.gc_wire.theWire(guids[0],guid_string);
		$(this).data('theWire', dc);
		dc.toggleEdit();
		return false;
	});
};

elgg.register_hook_handler('init', 'system', elgg.gc_wire.init);
