/**
 * elgg_layout css for Internet Explorer > ie6
 * @uses $vars['wwwroot'] The site URL
*/
.namefield a {
	float: left;
}
.elgg-avatar.elgg-avatar-tiny.tiny-float-left {
	float: left;
}
.elgg-button-submit {
	padding-left:3px;
	padding-right:3px;
}
.blackcommonbarright {
	width: 54% !important;
}
/* Fix friend picker */
.friends-picker {
	width:auto;
}
.friends-picker-navigation-l {
        right: 48px;
}
.friends-picker-navigation-r {
        right: 0;
}

/* group wall format */
#elgg-group-description {
        padding-bottom:0px;
}


/* For stupid focus in member text box
.elgg-inner {
	position: relative;
	z-index:10000;
}
.elgg-menu .elgg-child-menu {
	z-index:9000;
}
.elgg-input-text {
	display:inline;	
	position: relative;
	z-index:2000;
}
/* remove scrollbars in river textarea*/
textarea { overflow: auto;}

/* fix submit button opacity */
.elgg-button {
	zoom:1;
}

#thewire-submit-button {
	float: left;
}


/* fix space between comments and like */
.elgg-menu-item-comment {
	padding-right: 10px;
}
/* fix user menu */
.elgg-menu-hover{
	z-index:2000 !important;
}

/* Fix friends newest/popular/online tabs */
.elgg-tabs>li {
	display: inline;
}

/* Fix discussion page in groups */
.elgg-composer>h4 {
	display: inline;
}
.elgg-menu-composer {
	display: inline;
}
/* Trick to target only this page otherwise it breaks the river */
.elgg-menu-composer .elgg-menu-item-messageboard>a .elgg-icon-speech-bubble-alt{
	margin-left: 0px;
}

/* Fix for discussion page */
.elgg-menu-entity>li {
	display: inline;
}

/* Fix for gallery presentation in profile info and groups */
.elgg-gallery-fluid li {
	float: none;
}

/* fix scrolldown menu behind */
.elgg-ajax-loader,.elgg-messageboard {
	z-index:2000;
}
.elgg-menu-item-groups-join,.elgg-menu-item-add,.elgg-menu-item-message,.elgg-menu-item-editprofile,.elgg-composer,.elgg-menu,.elgg-menu-item-groups-invite,.elgg-menu-item-groups-edit,.elgg-menu-item-add,.elgg-menu-title-default,.elgg-menu-item-groups-leave,.elgg-head,.elgg-menu-item-groups-joinrequest,.elgg-menu-item-subpage {
	z-index:-1;
}

/* For stupid horizontal scrollbar problems */
.elgg-page-topbar>li>a {
	display:inline;	
	z-index:1000;
}
.elgg-menu-topbar>li>a {
	display:inline;	
	z-index:1000;
}


/* input box for the bookmark/file in the river composer */
.elgg-input-longtext {
	width: 97%;
}

/* input box for the wire in the river composer */
.elgg-input-plaintext {
	width: 97%;
}

/* avatar in online box */
.elgg-avatar-small {
	float: left;
	width: 40px;
	padding: 0px 1px;
}

/* position of activity box for ie7 */
.elgg-sidebar-alt-river,.elgg-sidebar-alt-river-activity {
	top: 40px;
}
/* bottom links to go forward and backward */
.elgg-pagination>li {
	display:inline-block;
	zoom: 1;
	*display:inline;
}
.elgg-pagination>.elgg-state-selected>span {
	padding-bottom: 2px;
}

/* For input boxes (username password) */
input { width: 90%;}

/* users/mail icon positioning */
.elgg-icon-mail {
	margin: 0px 20px;
}
/* composer icon positioning */
.elgg-icon-share,.elgg-icon-push-pin,.elgg-icon-speech-bubble,.elgg-icon-clip{
	position: absolute
}

.elgg-page-header:after {
        content: ".";
        display: block;
        height: 0;
        clear: both;
        visibility: hidden;
	float: none;
}
/* Pushing left menu down */
.elgg-page-header{ height: 190px; }

.elgg-page-body:before {
        content: ".";
        display: block;
        height: 0;
        clear: both;
        visibility: hidden;
	float: none
}
.elgg-page-body{ float: none; }


/* tools drop-down menu */
#elgg-header {z-index:1;}
.navigation li a:hover ul {display:block; position:absolute; top:21px; left:0;}
.navigation li a:hover ul li a {display:block;}
.navigation li.navigation-more ul li a {width:150px;background-color: #dedede;}

.clearfix { display: block;clear:both;}
.hidden.clearfix { display: none; }
#elgg-page-contents {overflow: hidden;} /* remove horizontal scroll on riverdash */
#breadcrumbs {top:-2px; margin-bottom: 5px;}

/* entity list views */
.entity-metadata {max-width: 300px;}
.entity-edit {float:right;}
.access_level {float:left;}
.elgg-image-block .entity-metadata {
	min-width:400px;
	text-align: right;
}

/* profile */
.elgg-tabs.profile .profile_name {margin-left: -260px;}
#profile_content .river_comment_form.hidden .input-text { width:510px; }

/* notifications */
.friends-picker-navigation {margin:0;padding:0;}
.friends-picker-container h3 {margin:0;padding:0;line-height: 1em;}

/* private messages */
#elgg-topbar-contents a.privatemessages.new span { 
	display:block;
	padding:1px;
	position:relative;
	text-align:center;
	float:left;
	top:-1px;
	right:auto;
}
#elgg-topbar-contents a.privatemessages.new  {padding:0 0 0 20px;}
#elgg-topbar-contents a.privatemessages:hover {background-position:left 2px;}
#elgg-topbar-contents a.privatemessages.new:hover {background-position: left 2px;}

/* riverdashboard mod rules */
#riverdashboard_updates {clear:both;}
#riverdashboard_updates a.update_link {margin:0 0 9px 0;}
.riverdashboard_filtermenu {margin:10px 0 0 0;}
.river_comment_form.hidden .input-text {
	width:530px;
	float:left;
}
.river_link_divider {
	width:10px;
	text-align: center;
}

/* shared access */
.shared_access_collection h2.shared_access_name {margin-top:-15px;}

/* dropdown login */
*:first-child+html #login-dropdown #signin-button {
	line-height:10px;
}
*:first-child+html #login-dropdown #signin-button a.signin span {
	background-position:-150px -54px;
}
*:first-child+html #login-dropdown #signin-button a.signin.menu-open span {
	background-position:-150px -74px;
}

/* Gallery */
.elgg-gallery-fluid > li {
	float: left;
	margin: 2px;
}


/* navigation */
.elgg-breadcrumbs > li {
	display: inline;
}

.elgg-breadcrumbs > li > a {
	display: inline;
	padding-right: 4px;
	margin-right: 4px;
	border-right: 1px solid #bababa;
}

.elgg-menu-title > li {
	display: block;
}

.elgg-menu-title > li > a {
	display: block;
}
/* for ie7 leave group/invite friends/edit group boxes */
.elgg-menu-title-default>li {
	display:inline-block;
	zoom: 1;
	*display:inline;
}

.elgg-menu-footer > li > a {
	display: inline;
}

.elgg-menu-river > li {
	display: inline;
}

li:hover > .elgg-menu-site-more {
	display: none;
}

/* admin */
.elgg-menu-footer li {
	display: inline;
}


* {zoom: 1;} /* trigger hasLayout in IE */
