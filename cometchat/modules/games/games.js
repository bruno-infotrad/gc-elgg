
var gamessource = {};
var gamesheight = {};
var gameswidth = {};
var categorygames = {};
var keywords = "{$keywordlist}";
var keywordmatch = new RegExp(keywords);

$(document).ready(function() {

	if (jQuery().slimScroll) {
		$("#games").slimScroll({height: '300px'});
	}

	$.getJSON('contents.php?get=categories', function(data) {
		if (data == '0') {
			$("body").html('<iframe width="100%" height="294" frameborder="0" scrolling="no" allowtransparency="true" vspace="0" hspace="0" marginheight="0" marginwidth="0" src="http://www.heyzap.com/embed?embed_key=2b9c74ca22&special_height=300" style="margin-top:3px;" id="heyzap_iframe"></iframe>');
		} else {
			var categoriesinfo = '';
			for (x = 0;x<data.categories.length;x++) {	
				if (x < 10) {
					categorygames[data.categories[x].name] = data.categories[x].num_games;
					categoriesinfo += '<li id=\''+data.categories[x].name+'\' onclick="javascript:getCategory(\''+data.categories[x].name+'\')">'+data.categories[x].display_name+'</li>';
				}
			}

			$('#categories').html(categoriesinfo);
			
			getCategory('featured');
		}
	});
}); 

function getCategory(catname,page){
	if (page == null) {
		page = 1;
	} else {
		page = parseInt(page);
	}
	
	$('#categories li').removeClass('catselected');
	$('#'+catname).addClass('catselected');

	$('#games').html('');
	$('#loader').css('display','block');

	$.getJSON('contents.php?get='+catname+'&page='+page, function(data) {
		
		$('#loader').css('display','none');
		for (x = 0;x<data.games.length;x++) {
			var name = data.games[x].display_name;
			var thumbnail = data.games[x].thumb_100x100;
			var width = data.games[x].width;
			var height = data.games[x].height;
			var source = data.games[x].embed_code;

			gamessource[x] = source;
			gamesheight[x] = height;
			gameswidth[x] = width;

			if (data.games[x].display_name.toLowerCase().match(keywordmatch) != null) {
			} else {
 				$('#games').append('<div class="gamelist" onclick="javascript:loadGame(\''+x+'\')"><img src="'+thumbnail+'"><br/>'+name+'</div>');
			}
		}
		if (jQuery().slimScroll) {
			$("#games").slimScroll({resize: '1'});
		}

	});
}

function loadGame(id) {

	var link = gamessource[id].match(/src="(.*?)"/g);
	link = link[0];
	link = link.substring(5,link.length-1)

	if (navigator.appName == 'Microsoft Internet Explorer') {
		w = window.open (link, 'singleplayergame',"status=0,toolbar=0,menubar=0,directories=0,resizable=0,location=0,status=0,scrollbars=0, width="+gameswidth[id]+",height="+gamesheight[id]); 
	} else {
		w = window.open ('', 'singleplayergame',"status=0,toolbar=0,menubar=0,directories=0,resizable=0,location=0,status=0,scrollbars=0, width="+gameswidth[id]+",height="+gamesheight[id]); 
		w.document.write('<!DOCTYPE html><html><body><style>html, body {padding:0;margin:0;overflow:hidden;}</style>');
		w.document.write(gamessource[id]);
		w.document.write('</body></html>');
	}
}