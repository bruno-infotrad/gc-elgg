<?php
	
		include dirname(__FILE__).DIRECTORY_SEPARATOR."lang/en.php";

		if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."lang/".$lang.".php")) {
			include dirname(__FILE__).DIRECTORY_SEPARATOR."lang/".$lang.".php";
		} 

		foreach ($block_language as $i => $l) {
			$block_language[$i] = str_replace("'", "\'", $l);
		}
?>

/*
 * CometChat
 * Copyright (c) 2012 Inscripts - support@cometchat.com | http://www.cometchat.com | http://www.inscripts.com
*/

(function($){   
  
	$.ccblock = (function () {

		var title = '<?php echo $block_language[0];?>';

        return {

			getTitle: function() {
				return title;	
			},

			init: function (id) {
				baseUrl = $.cometchat.getBaseUrl();
				baseData = $.cometchat.getBaseData();

				var result = confirm('<?php echo $block_language[1];?>');

				if (result) {
					$.getJSON(baseUrl+'plugins/block/index.php?action=block&callback=?', {to: id, basedata: baseData},
						function(data) {
							alert('<?php echo $block_language[2];?>');
							setTimeout(function() {
								if ($('#cometchat_user_'+id).length > 0) {
									$('#cometchat_user_'+id+' .cometchat_closebox_bottom').click();
								}
							}, 1000);
						}
					);
				}
			},

			addCode: function() {
				$('#cometchat_optionsbutton_popup .cometchat_tabcontent').append('<a href="javascript:void(0);" onclick="javascript:jqcc.ccblock.blockList();" style="margin-left:5px;margin-top:5px;"><?php echo $block_language[5];?></a>');
			},

			blockList: function (id) {
				baseUrl = $.cometchat.getBaseUrl();
				baseData = $.cometchat.getBaseData();
				loadCCPopup(baseUrl+'plugins/block/index.php?basedata='+baseData, 'blocks',"status=0,toolbar=0,menubar=0,directories=0,resizable=0,location=0,status=0,scrollbars=1, width=540,height=300",540,300,'<?php echo $block_language[3];?>');  
			}
        };
    })();
 
})(jqcc);