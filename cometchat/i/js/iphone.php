<?php
 
include dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."modules.php";
include dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."lang/en.php";

if (file_exists(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."lang/".$lang.".php")) {
	include dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."lang/".$lang.".php";
}

foreach ($i_language as $i => $l) {
	$i_language[$i] = str_replace("'", "\'", $l);
}

?>



(function($){   
		  
			$.iphone = (function () {

				var currentChatboxId = 0;
				var onlineScroll;
				var chatScroll;
				var hideWebBar;
				var keyboardOpen = 0;
				var landscapeMode = 0;
				var buddyListName = {};
				var buddyListAvatar = {};
				var buddyListMessages = {};
				var closedList = 0;
				var openList = 0;
				var longNameLength = 20;

				return {

					playSound: function() {
						var pElement = document.getElementById("audio");		
						setTimeout(function() {
							pElement.load();
							
							setTimeout(function() {
								pElement.play();
							}, 500);
						}, 500);
					},

					detect: function(keyboard) {
						var baseHeight = 368;
						var baseWidth = 320;
						var sBaseHeight = 366;
						var keyboardHeight = 268;

						if (window.innerWidth < 480) {
							baseWidth = 320;
							baseHeight = 368;
							sBaseHeight = 316;
							keyboardHeight = 216;
							closedList = 3;
							openList = 9;
						} else if (window.innerWidth == 480) {
							baseWidth = 480;
							baseHeight = 220;
							sBaseHeight = 168;
							keyboardHeight = 0;
							closedList = 0;
							openList = 4;
						} else if (window.innerWidth == 768) {
							baseWidth = 768;
							baseHeight = 880;
							sBaseHeight = 828;
							keyboardHeight = 308;
							closedList = 17;
							openList = 26;
						} else {
							baseWidth = window.innerWidth;
							baseHeight = 624;
							sBaseHeight = 572;
							keyboardHeight = 396;
							openList = 17;
							closedList = 7;
						}

						$("body").css('width',baseWidth+'px');
						$(".header").css('width',(baseWidth-14)+'px');
						$(".roundedtitle").css('width',(baseWidth-30)+'px');
						$(".footer").css('width',(baseWidth-14)+'px');
						$(".footer input").css('width',(baseWidth-28)+'px');
						$(".roundedcenter").css('width',(baseWidth-70)+'px');
						$("#wrapper").css('height',baseHeight+'px');

						if (keyboard) {
							$("#cwwrapper").css('height',sBaseHeight-keyboardHeight+'px');
						} else {
							$("#cwwrapper").css('height',sBaseHeight+'px');
						}

						window.scrollTo(0, 50000);


					},

					init: function() {

						jqcc.iphone.detect();

						window.addEventListener('onorientationchange' in window ? 'orientationchange' : 'resize', function() {
									jqcc.iphone.detect();
						}, false);

						document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);

						$('#header .roundedright').click(function() {
							location.href = jqcc.cometchat.getBaseUrl()+'../';
						});
						
						jqcc.iphone.hideBar();
						
						onlineScroll = new iScroll('scroller', {desktopCompatibility:true});

					},

					hideBar: function() {
						if (landscapeMode == 1) {
							$('#chatmessage').blur();
							window.scrollTo(0, 0);
						} else {
							window.scrollTo(0, 50000);
						}
						
						clearTimeout(hideWebBar);
						hideWebBar = setTimeout(function(){jqcc.iphone.hideBar()}, 1000);
					},

					updateBuddyList: function(data) {

						var buddylist = '';
						var buddylisttemp = {};

						buddylisttemp['available'] = '';
						buddylisttemp['busy'] = '';
						buddylisttemp['offline'] = '';
						buddylisttemp['away'] = '';
						
						$.each(data, function(i,buddy) {
								 
							if (buddy.n.length > longNameLength) {
								longname = buddy.n.substr(0,longNameLength)+'...';
							} else {
								longname = buddy.n;
							}

							buddyListName[buddy.id] = buddy.n;
							buddyListAvatar[buddy.id] = buddy.a;

							if (!buddyListMessages[buddy.id]) {
								buddyListMessages[buddy.id] = 0;
							}

							buddylisttemp[buddy.s] += '<li class="onlinelist" id="onlinelist_'+buddy.id+'" onclick="javascript:jqcc.iphone.loadPanel(\''+buddy.id+'\')"><img src="'+buddy.a+'" class="avatarimage">'+longname+'<div class="status">'+buddy.s+'</div><div class="newmessages"></div></li>';

							$('#onlinelist_'+buddy.id).remove();
						});

						buddylist = buddylisttemp['available']+buddylisttemp['busy']+buddylisttemp['away']+buddylisttemp['offline'];

						if (buddylist == '') {
							buddylist += '<li class="onlinelist" id="nousersonline">'+jqcc.cometchat.getLanguage(14)+'</li>';
						}

						$('#wolist').html(buddylist);
					},

					loggedOut: function() {
						alert('<?php echo $i_language[5];?>');
						location.href = jqcc.cometchat.getBaseUrl()+'../';
					},

					sendMessage: function(id) {
						var message = $('#chatmessage').val();
						$('#chatmessage').val('');
						jqcc.cometchat.sendMessage(id,message);
						$('#chatmessage').focus();

						fromname = '<?php echo $i_language[6];?>';
						selfstyle = 'selfmessage';

						var ts = Math.round(new Date().getTime() / 1000)+''+Math.floor(Math.random()*1000000);

						var temp = (('<li><div class="cometchat_chatboxmessage '+selfstyle+'" id="cometchat_message_'+ts+'"><span class="cometchat_chatboxmessagefrom"><strong>'+fromname+'</strong><?php echo $i_language[7];?></span><span class="cometchat_chatboxmessagecontent">'+message+'</span>'+'</div></li>'));

						if (currentChatboxId == id) {
							$('#cwlist').append(temp);

							if ($("#cwlist li").size() > closedList) {
								setTimeout(function () {chatScroll.scrollToElement('#cwendoftext')}, 200);
							} else {
								setTimeout(function () {chatScroll.scrollToElement('#cwendoftext','0ms')}, 200);
							}
							
						}

						return false;
					},

					newMessage: function(incoming) {

						if (!buddyListName[incoming.from]) {		
							jqcc.cometchat.getUserDetails(incoming.from);
						}

						fromname = buddyListName[incoming.from];

						if (fromname.indexOf(" ") != -1) {
							fromname = fromname.slice(0,fromname.indexOf(" "));
						}

						var ts = Math.round(new Date().getTime() / 1000)+''+Math.floor(Math.random()*1000000);

						var atleastOneNewMessage = 0;

						if (incoming.self == 0) {
							var temp = (('<li><div class="cometchat_chatboxmessage" id="cometchat_message_'+ts+'"><span class="cometchat_chatboxmessagefrom"><strong>'+fromname+'</strong><?php echo $i_language[7];?></span><span class="cometchat_chatboxmessagecontent">'+incoming.message+'</span>'+'</div>'));
							atleastOneNewMessage++;
						}

						if (currentChatboxId == incoming.from) {
							$('#cwlist').append(temp);

							if ((keyboardOpen == 1 && $("#cwlist li").size() < 4) || (keyboardOpen == 0 && $("#cwlist li").size() < 10)) {
								setTimeout(function () {chatScroll.scrollToElement('#cwendoftext','0ms')}, 200);
							} else {
								setTimeout(function () {chatScroll.scrollToElement('#cwendoftext')}, 200);
							}
									
						} else {
							if (buddyListMessages[incoming.from]) {
								buddyListMessages[incoming.from] += 1;
							} else {
								buddyListMessages[incoming.from] = 1;
							}

							$('#onlinelist_'+incoming.from+' .newmessages').html(buddyListMessages[incoming.from]);
						}

						if (atleastOneNewMessage) {
							jqcc.iphone.playSound();
						}

					},

					loadUserData: function(id,data) {
						buddyListName[id] = data.n;
						buddyListAvatar[id] = data.a;
					
						if (!buddyListMessages[id]) {
							buddyListMessages[id] = 0;
						}

						if (data.n.length > longNameLength) {
							longname = data.n.substr(0,longNameLength)+'...';
						} else {
							longname = data.n;
						}

						var buddylist = '<li class="onlinelist" id="onlinelist_'+data.id+'" onclick="javascript:jqcc.iphone.loadPanel(\''+data.id+'\')"><img src="'+data.a+'" class="avatarimage">'+longname+'<div class="status">'+data.s+'</div><div class="newmessages"></div></li>';

						$('#nousersonline').css('display','none');
						$('#permanent').prepend(buddylist);
					},

					newMessages: function(data) {

						var temp = '';
						var atleastOneNewMessage = 0;						

						$.each(data, function(i,incoming) {
									
								if (!buddyListName[incoming.from]) {
									jqcc.cometchat.getUserDetails(incoming.from);
								}


								fromname = buddyListName[incoming.from];

								if (fromname.indexOf(" ") != -1) {
									fromname = fromname.slice(0,fromname.indexOf(" "));
								}

								var ts = Math.round(new Date().getTime() / 1000)+''+Math.floor(Math.random()*1000000);

								if (incoming.self == 0) {
									var temp = (('<li><div class="cometchat_chatboxmessage" id="cometchat_message_'+ts+'"><span class="cometchat_chatboxmessagefrom"><strong>'+fromname+'</strong><?php echo $i_language[7];?></span><span class="cometchat_chatboxmessagecontent">'+incoming.message+'</span>'+'</div>'));
									atleastOneNewMessage++;

									if (currentChatboxId == incoming.from) {
										$('#cwlist').append(temp);
										
										if ((keyboardOpen == 1 && $("#cwlist li").size() < 4) || (keyboardOpen == 0 && $("#cwlist li").size() < openList)) {
											setTimeout(function () {chatScroll.scrollToElement('#cwendoftext','0ms')}, 200);
										} else {
											setTimeout(function () {chatScroll.scrollToElement('#cwendoftext')}, 200);
										}

									} else {
										if (buddyListMessages[incoming.from]) {
											buddyListMessages[incoming.from] += 1;
										} else {
											buddyListMessages[incoming.from] = 1;
										}


										$('#onlinelist_'+incoming.from+' .newmessages').html(buddyListMessages[incoming.from]);
									}

								}


						});
						
						if (atleastOneNewMessage) {
							jqcc.iphone.playSound();
						}
					},

					loadPanel: function (id,name) {

						buddyListMessages[id] = 0;
						$('#onlinelist_'+id+' .newmessages').html('');

						$('#chatwindow').html('<div id="cwheader" class="header"><div class="roundedleft">&nbsp;</div><div class="roundedcenter">'+buddyListName[id]+'</div><div class="roundedright"><?php echo $i_language[8];?></div><div style="clear:both"></div></div><div id="cwwrapper"><div id="cwscroller"><ul id="cwlist"></ul><div id="cwendoftext"></div></div></div><div id="cwfooter" class="footer"><form onsubmit="return jqcc.iphone.sendMessage(\''+id+'\')"><input type="text" name="chatmessage" placeholder="<?php echo $i_language[9];?>" id="chatmessage" /></div>');

						$('#whosonline').css('display','none');
						$('#chatwindow').css('display','block');

						setTimeout(function(){}, 1000);
						
						jqcc.iphone.detect();

						currentChatboxId = id;

						$('#cwfooter').click(function() {
							jqcc.iphone.detect(1);

							setTimeout(function () { chatScroll.refresh() }, 0);
							setTimeout(function () { $('#chatmessage').focus(); }, 100);
							setTimeout(function () {  }, 100);
							setTimeout(function () {chatScroll.scrollToElement('#cwendoftext','0ms')}, 200);
							keyboardOpen = 1;

							setTimeout(function () { window.scrollTo(0, 50000); }, 100);
							setTimeout(function () { $('#chatmessage').focus(); }, 200);
							
						});


						$('#chatwindow .roundedright').click(function() {
							jqcc.iphone.back();
						});

						$('#chatmessage').blur(function() {
							keyboardOpen = 0;
							jqcc.iphone.detect();
							setTimeout(function () { chatScroll.refresh() }, 0)
						});


						jqcc.cometchat.getRecentData(id);
						chatScroll = new iScroll('cwscroller');
						setTimeout(function () {chatScroll.scrollToElement('#cwendoftext','0ms')}, 200);

					},

					loadData: function (id,data) {
						$.each(data, function(type,item){
								if (type == 'messages') {

									var temp = '';
									$.each(item, function(i,incoming) {
										
										var selfstyle = '';
										if (incoming.self == 1) {
											fromname = '<?php echo $i_language[6];?>';
											selfstyle = 'selfmessage';
										} else {
											fromname = buddyListName[id];
										}

										var ts = new Date(incoming.sent * 1000);

										if (fromname.indexOf(" ") != -1) {
											fromname = fromname.slice(0,fromname.indexOf(" "));
										}
								
										temp += ('<li><div class="cometchat_chatboxmessage '+selfstyle+'" id="cometchat_message_'+incoming.id+'"><span class="cometchat_chatboxmessagefrom'+selfstyle+'"><strong>'+fromname+'</strong><?php echo $i_language[7];?></span><span class="cometchat_chatboxmessagecontent'+selfstyle+'">'+incoming.message+'</span>'+'</div>');

									});

									if (currentChatboxId == id) {
										$('#cwlist').append(temp);
										setTimeout(function () {chatScroll.scrollToElement('#cwendoftext','0ms')}, 200);			
									}
								}

							});
					},

					back: function() {
						$('#chatwindow').css('display','none');
						$('#chatwindow').html('');
						$('#whosonline').css('display','block');
						$('#onlinelist_'+currentChatboxId+' .newmessages').html('');
						currentChatboxId = 0;
					}

				};
			})();
		 
		})(jqcc);

		var listener = function (e) {
			e.preventDefault();
		};

window.onload = function() {
	jqcc.iphone.init();
}