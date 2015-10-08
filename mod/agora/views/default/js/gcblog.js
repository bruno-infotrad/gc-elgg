elgg.provide('elgg.gcblog');

elgg.gcblog.init = function() {
	$('#blog_status').live('change', elgg.gcblog.comments_on_off);
};

elgg.gcblog.comments_on_off = function(event) {
        var selectedValue = $(this).val();
        
        if(selectedValue  === 'published') {
            $("#blog_comments_on").append($('<option></option>', { 'value' : 'On' }) .text(elgg.echo('on')));
            $('#blog_comments_on').val('On');
        } else if (selectedValue === 'draft') {
            $('#blog_comments_on').val('Off');
            $("#blog_comments_on Option[value='On']").remove();
        }
};

elgg.register_hook_handler('init', 'system', elgg.gcblog.init);
