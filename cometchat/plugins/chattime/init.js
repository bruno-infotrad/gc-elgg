<?php

		include dirname(__FILE__).DIRECTORY_SEPARATOR."lang/en.php";

		if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."lang/".$lang.".php")) {
			include dirname(__FILE__).DIRECTORY_SEPARATOR."lang/".$lang.".php";
		}

		foreach ($chattime_language as $i => $l) {
			$chattime_language[$i] = str_replace("'", "\'", $l);
		}
 
?>

/*
 * CometChat
 * Copyright (c) 2012 Inscripts - support@cometchat.com | http://www.cometchat.com | http://www.inscripts.com
*/

(function($){   
  
	$.ccchattime = (function () {

		var title = '<?php echo $chattime_language[0];?>';

        return {

			getTitle: function() {
				return title;	
			},

			init: function (id) {

				if ($("#cometchat_user_"+id+"_popup .cometchat_ts").css('display') == 'none') {
					$("#cometchat_user_"+id+"_popup .cometchat_ts").css('display','inline');
					$("#cometchat_tabcontenttext_"+id).scrollTop(50000);
				} else {
					$("#cometchat_user_"+id+"_popup .cometchat_ts_date").css('display','none');
					$("#cometchat_user_"+id+"_popup .cometchat_ts").css('display','none');					
				}
			}

        };
    })();
 
})(jqcc);