<script>
$(document).ready(function() {
	/*var showMoreText = '&nbsp;&nbsp;&nbsp; '+elgg.echo("gc_theme:more"), showLessText = '&nbsp;&nbsp;&nbsp; '+elgg.echo("gc_theme:less"),*/
	var showMoreText = '&nbsp;&nbsp;&nbsp; <?php echo elgg_echo("gc_theme:more"); ?>',
	showLessText = '&nbsp;&nbsp;&nbsp; <?php echo elgg_echo("gc_theme:less"); ?>',
        collapsedHeight = $('<div>', {'class': 'text collapsed'}).css('height'),
        addExpander = function() {
        	if (this.scrollHeight <= parseInt(collapsedHeight)) {
        	  $(this).find('.enlarge').remove();  
        	}
        	else if (!$(this).siblings('.enlarge')[0]) {
        	    $('<div>', {'class': 'enlarge'}).html(showMoreText)
        	        .on('click',function() {
        	            var btn = $(this),
        	                text = btn.siblings('.text'),
        	                collapsed = text.hasClass('collapsed'),
        	                // predict the height that the element SHOULD be: its full (scroll) height if it is currently collapsed, or the value of the 'height' CSS property that it will have when collapsed if it is not currently collapsed
        	                height = collapsed ? text[0].scrollHeight : collapsedHeight;
        	            text.animate({'height': height}, 40, function() {
        	                // do all further changes after the animation completes
        	               text.toggleClass('collapsed'); // we use this class for our animation logic, so we need to toggle it correctly
        	               text.css('height', ''); // we don't want the text element to have a fixed height, so remove whatever height we set earlier
				if (collapsed) {
					btn.css("background-position","0 -1476px");
				} else {
					btn.css("background-position","0 -1548px");
				}
        	               btn.html(collapsed ? showLessText : showMoreText);
        	            });    
        	    }).insertAfter(this);                    
        	}
    	};
    	$('.text.collapsed').each(addExpander);
});
</script>
