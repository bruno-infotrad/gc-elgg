<?php

?>
elgg.provide('elgg.scroll');

elgg.scroll = function(base_url,context,page_type,owner,offset,count,iteration,already_viewed){
        var data;
        if (elgg.is_logged_in()) {
        	$(document).ready(function() {
			var ajax_path, context_atoms,new_offset, more_url, params, groups_page_type;
			var delete_marker = false, more_marker;
			var path = base_url.replace(elgg.get_site_url(),'');
			var path_atoms = path.split("/");
			//console.log('BEFORE REWRITE context='+context+' page_type='+page_type+' path='+path+' path_atoms='+JSON.stringify(path_atoms));
			if (! context ) {
				context_atoms = path_atoms[0].split("?");
				if(context_atoms.length > 1) {
					context = context_atoms[0];
					page_type_atoms = context_atoms[1].split("=");
					page_type = page_type_atoms[1];
				} else {
					context = path_atoms[0];
					if (path_atoms[1]) {
						page_type = path_atoms[1];
						page_type_atoms = path_atoms[1].split("?");
						if(page_type_atoms.length > 1) {
							groups_page_type = page_type_atoms[0];
							filter_atoms = page_type_atoms[1].split("=");
							filter_type = filter_atoms[0];
							filter_value = filter_atoms[1];
							//console.log('IN IF AFTER REWRITE context='+context+' page_type='+page_type+' path='+path+' path_atoms='+JSON.stringify(path_atoms)+' filter_atoms='+JSON.stringify(filter_atoms));
						}
					}
				}
			}
			if (! page_type) {
				page_type = path_atoms[1];
			}
			if (page_type === undefined) {
				page_type = '';
			}
			if (context == 'embed') {
				more_marker = '#colorbox ul.elgg-pagination';
				$(more_marker).hide();
				$(more_marker).before('<div class="elgg-ajax-loader" id="gc-pagination"></div>');
			} else {
				more_marker = 'ul.elgg-pagination';
				$(more_marker).hide();
				$(more_marker).before('<div class="elgg-ajax-loader" id="gc-pagination"></div>');
			}
			//console.log('AFTER REWRITE context='+context+' page_type='+page_type+' owner='+owner+' path='+path+' path_atoms='+JSON.stringify(path_atoms)+' already viewed='+already_viewed);
			// Fix for colorbox issue (embed)
			switch(context){
				case 'search':
					iteration++;
					delete_marker = elgg.delete_marker(count,iteration,10);
					new_offset = parseInt(offset) + 10;
					//console.log('base_url='+base_url+' context='+context+' page_type='+page_type+' owner='+owner+' offset='+offset);
					more_url = elgg.more_url(base_url,context,page_type,owner,new_offset,count,iteration);
					//console.log('more_url='+more_url);
					params = {'base_url': base_url,'page_type': page_type,'owner': owner, 'offset': new_offset};
					ajax_path = 'ajax/view/gc_theme/ajax/'+path_atoms;
					break;
				case 'dashboard':
					iteration++;
					delete_marker = elgg.delete_marker(count,iteration,20);
					new_offset = parseInt(offset) + 20;
					//console.log('base_url='+base_url+' context='+context+' page_type='+page_type+' owner='+owner+' offset='+offset);
					more_url = elgg.more_url(base_url,context,page_type,owner,new_offset,count,iteration);
					//console.log('more_url='+more_url);
					params = {'base_url': base_url,'page_type': page_type,'owner': owner, 'offset': new_offset, 'already_viewed': already_viewed};
					ajax_path ='ajax/view/gc_theme/ajax/dashboard';
					break;
				case 'friends':
					iteration++;
					delete_marker = elgg.delete_marker(count,iteration,20);
					new_offset = parseInt(offset) + 20;
					more_url = elgg.more_url(base_url,context,page_type,owner,new_offset,count,iteration);
					//console.log('base_url='+base_url+' context='+context+' page_type='+page_type+' owner='+owner+' offset='+offset);
					params = {'base_url': base_url,'page_type': page_type,'owner': owner, 'offset': new_offset};
					ajax_path = 'ajax/view/gc_theme/ajax/friends';
					break;
				case 'friendsof':
					iteration++;
					delete_marker = elgg.delete_marker(count,iteration,20);
					new_offset = parseInt(offset) + 20;
					more_url = elgg.more_url(base_url,context,page_type,owner,new_offset,count,iteration);
					//console.log('base_url='+base_url+' context='+context+' page_type='+page_type+' owner='+owner+' offset='+offset);
					params = {'base_url': base_url,'page_type': page_type,'owner': owner, 'offset': new_offset};
					ajax_path = 'ajax/view/gc_theme/ajax/friendsof';
					break;
				case 'members':
					iteration++;
					if (page_type == 'all') {
						new_offset = parseInt(offset) + 15;
						//Will never get there unless everyone at DFATD is fired but what the heck :-)
						delete_marker = elgg.delete_marker(count,iteration,15);
					} else {
						new_offset = parseInt(offset) + 10;
						delete_marker = elgg.delete_marker(count,iteration,10);
					}
					more_url = elgg.more_url(base_url,context,page_type,owner,new_offset,count,iteration);
					//console.log('base_url='+base_url+' context='+context+' page_type='+page_type+' owner='+owner+' offset='+offset);
					params = {'base_url': base_url,'page_type': page_type,'owner': owner, 'offset': new_offset};
					if (page_type == 'search') {
						ajax_path = 'ajax/view/gc_theme/ajax/members/search/'+path_atoms[2];
					} else {
						ajax_path = 'ajax/view/gc_theme/ajax/members';
					}
					break;
				case 'bookmarks':
					if (page_type == 'all') {
						iteration++;
						delete_marker = elgg.delete_marker(count,iteration,12);
						new_offset = parseInt(offset) + 10;
						more_url = elgg.more_url(base_url,context,page_type,owner,new_offset,count,iteration);
						//console.log('BOOKMARKS base_url='+base_url+' context='+context+' page_type='+page_type+' owner='+owner+' offset='+offset);
						params = {'base_url': base_url,'page_type': page_type,'owner': owner, 'offset': new_offset};
						ajax_path = 'ajax/view/gc_theme/ajax/bookmarks_all';
					} else if (page_type == 'friends') {
						iteration++;
						delete_marker = elgg.delete_marker(count,iteration,20);
						new_offset = parseInt(offset) + 10;
						more_url = elgg.more_url(base_url,context,page_type,owner,new_offset,count,iteration);
						//console.log('base_url='+base_url+' context='+context+' page_type='+page_type+' owner='+owner+' offset='+offset);
						params = {'base_url': base_url,'page_type': page_type,'owner': owner, 'offset': new_offset};
						//Offset only obtained from get_input, not from options - blah
						ajax_path = 'ajax/view/gc_theme/ajax/bookmarks_friends?offset='+new_offset;
					} else if (page_type == 'owner') {
						iteration++;
						delete_marker = elgg.delete_marker(count,iteration,20);
						new_offset = parseInt(offset) + 10;
						more_url = elgg.more_url(base_url,context,page_type,owner,new_offset,count,iteration);
						//console.log('base_url='+base_url+' context='+context+' page_type='+page_type+' owner='+owner+' offset='+offset);
						params = {'base_url': base_url,'page_type': page_type,'owner': owner, 'offset': new_offset};
						ajax_path = 'ajax/view/gc_theme/ajax/bookmarks_owner';
					} else if (page_type == 'group') {
						var group_guid = path_atoms[2];
						iteration++;
						delete_marker = elgg.delete_marker(count,iteration,10);
						new_offset = parseInt(offset) + 10;
						more_url = elgg.more_url(base_url,context,page_type,owner,new_offset,count,iteration);
						//console.log('base_url='+base_url+' context='+context+' page_type='+page_type+' owner='+owner+' offset='+offset);
						params = {'base_url': base_url,'group_guid': group_guid, 'page_type': page_type, 'owner': owner, 'offset': new_offset};
						ajax_path = 'ajax/view/gc_theme/ajax/bookmarks_group';
					} else if (page_type == 'view') {
						owner = path_atoms[2];
						iteration++;
						delete_marker = elgg.delete_marker(count,iteration,5);
						new_offset = parseInt(offset) + 5;
						more_url = elgg.more_url(base_url,context,page_type,owner,new_offset,count,iteration);
						//console.log('base_url='+base_url+' context='+context+' page_type='+page_type+' owner='+owner+' offset='+offset);
						params = {'base_url': base_url,'group_guid': group_guid, 'page_type': page_type, 'owner': owner, 'offset': new_offset};
						ajax_path = 'ajax/view/gc_theme/ajax/comments';
					}
					break;

				case 'file':
					if (page_type == 'all') {
						iteration++;
						delete_marker = elgg.delete_marker(count,iteration,12);
						new_offset = parseInt(offset) + 12;
						more_url = elgg.more_url(base_url,context,page_type,owner,new_offset,count,iteration);
						//console.log('base_url='+base_url+' context='+context+' page_type='+page_type+' owner='+owner+' offset='+offset);
						params = {'base_url': base_url,'page_type': page_type,'owner': owner, 'offset': new_offset};
						ajax_path = 'ajax/view/gc_theme/ajax/file_world';
					} else if (page_type == 'friends') {
						iteration++;
						delete_marker = elgg.delete_marker(count,iteration,20);
						new_offset = parseInt(offset) + 20;
						more_url = elgg.more_url(base_url,context,page_type,owner,new_offset,count,iteration);
						//console.log('base_url='+base_url+' context='+context+' page_type='+page_type+' owner='+owner+' offset='+offset);
						params = {'base_url': base_url,'page_type': page_type,'owner': owner, 'offset': new_offset};
						//Offset only obtained from get_input, not from options - blah
						ajax_path = 'ajax/view/gc_theme/ajax/file_friends?offset='+new_offset;
					} else if (page_type == 'owner') {
						iteration++;
						delete_marker = elgg.delete_marker(count,iteration,20);
						new_offset = parseInt(offset) + 20;
						more_url = elgg.more_url(base_url,context,page_type,owner,new_offset,count,iteration);
						//console.log('base_url='+base_url+' context='+context+' page_type='+page_type+' owner='+owner+' offset='+offset);
						params = {'base_url': base_url,'page_type': page_type,'owner': owner, 'offset': new_offset};
						ajax_path = 'ajax/view/gc_theme/ajax/file_owner';
					}
					break;
				case 'pages':
					if (page_type == 'all') {
						iteration++;
						delete_marker = elgg.delete_marker(count,iteration,10);
						new_offset = parseInt(offset) + 10;
						more_url = elgg.more_url(base_url,context,page_type,owner,new_offset,count,iteration);
						//console.log('base_url='+base_url+' context='+context+' page_type='+page_type+' owner='+owner+' offset='+offset);
						params = {'base_url': base_url,'page_type': page_type,'owner': owner, 'offset': new_offset};
						ajax_path = 'ajax/view/gc_theme/ajax/pages_world';
					} else if (page_type == 'friends') {
						iteration++;
						delete_marker = elgg.delete_marker(count,iteration,10);
						new_offset = parseInt(offset) + 10;
						more_url = elgg.more_url(base_url,context,page_type,owner,new_offset,count,iteration);
						//console.log('base_url='+base_url+' context='+context+' page_type='+page_type+' owner='+owner+' offset='+offset);
						params = {'base_url': base_url,'page_type': page_type,'owner': owner, 'offset': new_offset};
						//Offset only obtained from get_input, not from options - blah
						ajax_path = 'ajax/view/gc_theme/ajax/pages_friends?offset='+new_offset;
					} else if (page_type == 'owner') {
						iteration++;
						delete_marker = elgg.delete_marker(count,iteration,10);
						new_offset = parseInt(offset) + 10;
						more_url = elgg.more_url(base_url,context,page_type,owner,new_offset,count,iteration);
						//console.log('base_url='+base_url+' context='+context+' page_type='+page_type+' owner='+owner+' offset='+offset);
						params = {'base_url': base_url,'page_type': page_type,'owner': owner, 'offset': new_offset};
						ajax_path = 'ajax/view/gc_theme/ajax/pages_owner';
					} else if (page_type == 'group') {
						var group_guid = path_atoms[2];
						iteration++;
						delete_marker = elgg.delete_marker(count,iteration,10);
						new_offset = parseInt(offset) + 10;
						more_url = elgg.more_url(base_url,context,page_type,owner,new_offset,count,iteration);
						//console.log('base_url='+base_url+' context='+context+' page_type='+page_type+' owner='+owner+' offset='+offset+' group_guid='+group_guid);
						params = {'base_url': base_url,'group_guid': group_guid, 'page_type': page_type, 'owner': owner, 'offset': new_offset};
						ajax_path = 'ajax/view/gc_theme/ajax/pages_owner';
					}
					break;
				case 'groups':
					if (page_type == 'profile') {
						var group_guid = path_atoms[2];
						iteration++;
						delete_marker = elgg.delete_marker(count,iteration,20);
						new_offset = parseInt(offset) + 20;
						more_url = elgg.more_url(base_url,context,page_type,owner,new_offset,count,iteration);
						//console.log('base_url='+base_url+' context='+context+' page_type='+page_type+' owner='+owner+' offset='+offset+' group_guid='+group_guid);
						//console.log('more_url='+more_url);
						params = {'base_url': base_url,'group_guid': group_guid, 'page_type': page_type, 'owner': owner, 'offset': new_offset};
						ajax_path = 'ajax/view/gc_theme/ajax/group_wall';
					} else if (page_type == 'members') {
						var group_guid = path_atoms[2];
						iteration++;
						delete_marker = elgg.delete_marker(count,iteration,20);
						new_offset = parseInt(offset) + 20;
						more_url = elgg.more_url(base_url,context,page_type,owner,new_offset,count,iteration);
						//console.log('base_url='+base_url+' context='+context+' page_type='+page_type+' owner='+owner+' offset='+offset+' group_guid='+group_guid);
						params = {'base_url': base_url,'group_guid': group_guid, 'page_type': page_type, 'owner': owner, 'offset': new_offset};
						ajax_path = 'ajax/view/gc_theme/ajax/group_members';
					} else if (groups_page_type == 'all') {
						iteration++;
						delete_marker = elgg.delete_marker(count,iteration,10);
						new_offset = parseInt(offset) + 10;
						// Void context
						more_url = elgg.more_url(base_url,'',page_type,owner,new_offset,count,iteration);
						//console.log('base_url='+base_url+' context='+context+' page_type='+page_type+' owner='+owner+' offset='+offset+' group_guid='+group_guid);
						//console.log('more_url='+more_url);
						params = {'base_url': base_url,'group_guid': group_guid, 'page_type': page_type, 'filter': filter_value, 'owner': owner, 'offset': new_offset};
						ajax_path = 'ajax/view/gc_theme/ajax/allgroups';
					}
					break;
				case 'polls':
					var group_guid = path_atoms[2];
					iteration++;
					delete_marker = elgg.delete_marker(count,iteration,15);
					new_offset = parseInt(offset) + 15;
					more_url = elgg.more_url(base_url,context,page_type,owner,new_offset,count,iteration);
					//console.log('base_url='+base_url+' context='+context+' page_type='+page_type+' owner='+owner+' offset='+offset+' group_guid='+group_guid);
					params = {'base_url': base_url,'group_guid': group_guid, 'page_type': page_type, 'owner': owner, 'offset': new_offset};
					ajax_path = 'ajax/view/gc_theme/ajax/polls';
					break;
				case 'blog':
					var group_guid;
					if (page_type == 'group') {
						group_guid = path_atoms[2];
					}
					iteration++;
					delete_marker = elgg.delete_marker(count,iteration,10);
					new_offset = parseInt(offset) + 10;
					more_url = elgg.more_url(base_url,context,page_type,owner,new_offset,count,iteration);
					//console.log('base_url='+base_url+' context='+context+' page_type='+page_type+' owner='+owner+' offset='+offset+' group_guid='+group_guid);
					params = {'base_url': base_url,'group_guid': group_guid, 'page_type': page_type, 'owner': owner, 'offset': new_offset};
					ajax_path = 'ajax/view/gc_theme/ajax/blogs';
					break;
				case 'thewire':
				case 'thewire_group':
					var group_guid;
					if (page_type == 'group') {
						group_guid = path_atoms[2];
					}
					if (! page_type) {
						page_type = 'all';
					}
					iteration++;
					delete_marker = elgg.delete_marker(count,iteration,15);
					new_offset = parseInt(offset) + 15;
					more_url = elgg.more_url(base_url,context,page_type,owner,new_offset,count,iteration);
					//console.log('base_url='+base_url+' context='+context+' page_type='+page_type+' owner='+owner+' offset='+offset+' group_guid='+group_guid);
					params = {'base_url': base_url,'group_guid': group_guid, 'page_type': page_type, 'owner': owner, 'offset': new_offset};
					ajax_path = 'ajax/view/gc_theme/ajax/thewire';
					break;
				case 'messages':
					if (page_type == 'inbox') {
						owner = path_atoms[2];
					}
					iteration++;
					delete_marker = elgg.delete_marker(count,iteration,10);
					new_offset = parseInt(offset) + 10;
					more_url = elgg.more_url(base_url,context,page_type,owner,new_offset,count,iteration);
					//console.log('base_url='+base_url+' context='+context+' page_type='+page_type+' owner='+owner+' offset='+offset+' group_guid='+group_guid);
					params = {'base_url': base_url,'group_guid': group_guid, 'page_type': page_type, 'owner': owner, 'offset': new_offset};
					ajax_path = 'ajax/view/gc_theme/ajax/messages_inbox';
					break;
				case 'site_notifications':
					owner = path_atoms[2];
					iteration++;
					delete_marker = elgg.delete_marker(count,iteration,10);
					new_offset = parseInt(offset) + 10;
					more_url = elgg.more_url(base_url,context,page_type,owner,new_offset,count,iteration);
					//console.log('base_url='+base_url+' context='+context+' page_type='+page_type+' owner='+owner+' offset='+offset+' group_guid='+group_guid);
					params = {'base_url': base_url,'group_guid': group_guid, 'page_type': page_type, 'owner': owner, 'offset': new_offset};
					ajax_path = 'ajax/view/gc_theme/ajax/site_notifications';
					break;
				case 'embed':
					var group_guid = page_type
					iteration++;
					delete_marker = elgg.delete_marker(count,iteration,6);
					new_offset = parseInt(offset) + 6;
					//console.log('EMBED base_url='+base_url+' context='+context+' page_type='+page_type+' owner='+owner+' offset='+offset+' group_guid='+group_guid);
					more_url = elgg.more_url(base_url,context,page_type,owner,new_offset,count,iteration);
					//console.log('EMBED more_url='+more_url);
					params = {'base_url': base_url,'group_guid': group_guid, 'page_type': page_type, 'owner': owner, 'offset': new_offset};
					ajax_path = 'ajax/view/gc_theme/ajax/embed';
					break;
				case 'discussion':
					owner = path_atoms[2];
					iteration++;
					delete_marker = elgg.delete_marker(count,iteration,10);
					new_offset = parseInt(offset) + 10;
					more_url = elgg.more_url(base_url,context,page_type,owner,new_offset,count,iteration);
					//console.log('base_url='+base_url+' context='+context+' page_type='+page_type+' owner='+owner+' offset='+offset+' group_guid='+group_guid);
					params = {'base_url': base_url,'group_guid': group_guid, 'page_type': page_type, 'owner': owner, 'offset': new_offset};
					ajax_path = 'ajax/view/gc_theme/ajax/replies';
					break;
				case 'ajax':
					if  (path_atoms[2] == 'profile') {
						var args=path_atoms[3].split("?");
						if (args[0] == 'user_groups') {
							var container = args[1].split("=");
							var container_guid = container[1];
							iteration++;
							delete_marker = elgg.delete_marker(count,iteration,10);
							new_offset = parseInt(offset) + 10;
							//console.log('base_url='+base_url+' context='+context+' page_type='+page_type+' owner='+owner+' offset='+offset+' group_guid='+group_guid);
							more_url = elgg.more_url(base_url,context,page_type,owner,new_offset,count,iteration);
							params = {'base_url': base_url,'container_guid': container_guid, 'page_type': page_type, 'owner': owner, 'offset': new_offset};
							ajax_path = 'ajax/view/gc_theme/ajax/user_groups';
						} else if (args[0] == 'user_activity') {
							var container = args[1].split("=");
							var container_guid = container[1];
							iteration++;
							delete_marker = elgg.delete_marker(count,iteration,20);
							new_offset = parseInt(offset) + 20;
							more_url = elgg.more_url(base_url,context,page_type,owner,new_offset,count,iteration);
							//console.log('base_url='+base_url+' context='+context+' page_type='+page_type+' owner='+owner+' offset='+offset+' group_guid='+group_guid);
							params = {'base_url': base_url,'container_guid': container_guid, 'page_type': page_type, 'owner': owner, 'offset': new_offset};
							ajax_path = 'ajax/view/gc_theme/ajax/user_activity';
						} else if (args[0] == 'user_colleagues') {
							var container = args[1].split("=");
							var container_guid = container[1];
							iteration++;
							delete_marker = elgg.delete_marker(count,iteration,20);
							new_offset = parseInt(offset) + 20;
							more_url = elgg.more_url(base_url,context,page_type,owner,new_offset,count,iteration);
							//console.log('base_url='+base_url+' context='+context+' page_type='+page_type+' owner='+owner+' offset='+offset+' group_guid='+group_guid);
							params = {'base_url': base_url,'container_guid': container_guid, 'page_type': page_type, 'owner': owner, 'offset': new_offset};
							ajax_path = 'ajax/view/gc_theme/ajax/user_colleagues';
						}
					}
					break;
			};
			elgg.get(ajax_path, {
				data: params, 
				dataType: 'html', 
				success: function(data) {
					//console.log("CONTEXT="+context);
					//data=data.replace(/<ul class="elgg-pagination">.+<\/ul>/,'');
					//console.log(data);
					$(more_marker).remove();
					var tmp_more_marker = '#gc-pagination';
					//var tmp_more_marker = '.elgg-ajax-loader';
					if (context == 'embed') {
						tmp_more_marker = '#colorbox #gc-pagination';
 						$(tmp_more_marker).before(data);
 						$(tmp_more_marker).replaceWith('');
					} else {
 						$(tmp_more_marker).before(data);
 						$(tmp_more_marker).replaceWith('');
					}
					if (delete_marker) {
						$(more_marker).remove();
					//} else {
						//$(more_marker).replaceWith(more_url);
					}
					$(more_marker).show();
				}
			});
			return false;
		});
	};
};
elgg.delete_marker = function(count,iteration,offset_step) {
	var delete_marker = false;
	if (count < (iteration+1)*offset_step) {
		delete_marker = true;
	}
	return delete_marker;
}
elgg.more_url = function(base_url,context,page_type,owner,offset,count,iteration){
	var concat='?';
	var pagination_more='<?php echo elgg_echo('gc_theme:more'); ?>';
	var patt=new RegExp("\\?");
	if (patt.test(base_url)) {concat = '&';}
	return "<ul class='elgg-pagination'><a href="+base_url+concat+"offset="+offset+" onclick=\"elgg.scroll('"+base_url+"','"+context+"','"+page_type+"','"+owner+"','"+offset+"','"+count+"','"+iteration+"');return false;\">"+pagination_more+"</a></ul>";
}
