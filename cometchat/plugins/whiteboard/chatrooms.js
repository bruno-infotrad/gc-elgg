<?php

		include dirname(__FILE__).DIRECTORY_SEPARATOR."lang/en.php";

		if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."lang/".$lang.".php")) {
			include dirname(__FILE__).DIRECTORY_SEPARATOR."lang/".$lang.".php";
		}

		foreach ($whiteboard_language as $i => $l) {
			$whiteboard_language[$i] = str_replace("'", "\'", $l);
		}
?>

/*
 * CometChat
 * Copyright (c) 2012 Inscripts - support@cometchat.com | http://www.cometchat.com | http://www.inscripts.com
*/

(function($){   
  
	$.ccwhiteboard = (function () {

		var title = '<?php echo $whiteboard_language[0];?>';
		var lastcall = 0;

        return {

			getTitle: function() {
				return title;	
			},

			init: function (id) {
				var currenttime = new Date();
				currenttime = parseInt(currenttime.getTime()/1000);
				if (currenttime-lastcall > 10) {
					baseUrl = getBaseUrl();

					var random = currenttime;
					loadCCPopup(baseUrl+'plugins/whiteboard/index.php?action=whiteboard&chatroommode=1&subaction=request&id='+id, 'whiteboard',"status=0,toolbar=0,menubar=0,directories=0,resizable=1,location=0,status=0,scrollbars=0, width=640,height=480",640,480,'<?php echo $whiteboard_language[9];?>',1);
				} else {
					alert('<?php echo $whiteboard_language[1];?>');
				}
			},

			accept: function (id) {
				baseUrl = getBaseUrl();
				loadCCPopup(baseUrl+'plugins/whiteboard/index.php?action=whiteboard&chatroommode=1&id='+id, 'whiteboard',"status=0,toolbar=0,menubar=0,directories=0,resizable=1,location=0,status=0,scrollbars=0, width=640,height=480",640,480,'<?php echo $whiteboard_language[9];?>',1); 
			}
        };
    })();
 
})(jqcc);