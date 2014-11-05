 (function($){  
     $.fn.simpleScrollableTab = function(options){
         return this.each(function(){
             var $tabs = $(this);
             var $tabsNav = $tabs.find('.ui-tabs-nav');
             $tabs.css({'padding':2, 'position':'relative'})
         .prepend('<div class="sst-tabs-arrows-protector"><span class="ui-icon ui-icon-carat-1-e sst-arrow" data-direction="left"></span><span class="ui-icon ui-icon-carat-1-w sst-arrow" data-direction="right"></span></div>')
         .disableSelection();
       var $arrows = $('.sst-tabs-arrows-protector');
       $arrows.css({width: '12px', height: '28px', margin: '0', float: 'left'});
             $tabsNav.wrap('<div id="sst-nav" class="sst-nav" style="width: 95%;position:relative;overflow:hidden;"/>');
       $tabsNav.css({'width':'9999px','margin-left': 0});
       var $nav = $('div#sst-nav.sst-nav');
       $nav.width($tabs.width(true) - $arrows.outerWidth());
       $('.sst-arrow').click(function() {
         var selected = $tabs.tabs('option', 'selected');
         var last_index = $tabs.tabs('length') - 1;
         if ($(this).data('direction') == 'left') {
           selected--;
           if (selected < 0 ) selected = last_index;
         } else {
           selected++;
           if (selected > last_index) selected = 0;
         }  
         $tabs.tabs('select', selected);
       });
       $tabs.bind('tabsselect',function(event, ui) {
         setTimeout(function() {
           var tab = $tabsNav.find('li.ui-tabs-selected');
           var outer = 0;
           $tabsNav.children('li').map(function() { outer += $(this).outerWidth()});
           if (outer >= $nav.width()-100) {
             var scrollSettings = { axis:'x', offset: { left: -500 } }
             $nav.scrollTo(tab, 100, scrollSettings);
           }
         }, 250);
       });
       $tabs.bind('tabsadd', function(event, ui) {
         $('div' + $(ui.tab).attr('href')).appendTo($tabs);
         ui.panel = $('div' + $(ui.tab).attr('href'))[0];
       });
       $tabs.bind('tabsremove', function(event, ui) {
         1;
       });
         });
     }
 })(jQuery);
