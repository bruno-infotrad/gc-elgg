<?php
?>
.elgg-menu-item-groups-yours-more {
	margin-left: -1.4em;
}
.elgg-menu-item-add {
  padding-top: 4px;
}
.elgg-menu-filter > li.pdg > a {color:#000;}
#elgg-menu-group-filter-container>.menu-viewport>.menu-overview>ul {min-width: 433px;}

#elgg-menu-group-filter-container .menu-viewport { overflow: hidden; position: relative;height: 40px }
#elgg-menu-group-filter-container .menu-overview { list-style: none; position: absolute; left: 0; top: 0; }
#elgg-menu-group-filter-container .menu-thumb .end,
#elgg-menu-group-filter-container .menu-thumb { background-color: #BBBBBB; border-radius: 5px;}
#elgg-menu-group-filter-container .menu-scrollbar { position: relative; float: right; width: 100%; top: 13px }
#elgg-menu-group-filter-container .menu-track { background-color: #EFEFEF; height: 10px; width:100%; position: absolute; top: 23px;padding: 0 1px; }
#elgg-menu-group-filter-container .menu-thumb { height: 10px; width: 10%; cursor: pointer; overflow: hidden; position: absolute; top: 0; }
#elgg-menu-group-filter-container .menu-thumb .end { overflow: hidden; height: 5px; width: 10%; }
#elgg-menu-group-filter-container .disable{ display: none; }

.elgg-sidebar-alt-river-activity .viewport { overflow: hidden; position: relative; }
.elgg-sidebar-alt-river-activity .overview { list-style: none; position: absolute; left: 0; top: 0; }
.elgg-sidebar-alt-river-activity .thumb .end,
.elgg-sidebar-alt-river-activity .thumb { background-color: #BBBBBB; border-radius: 5px;}
.elgg-sidebar-alt-river-activity .scrollbar { position: relative; float: right; width: 10px; }
.elgg-sidebar-alt-river-activity .track { background-color: #EFEFEF; height: 100%; width:7px; position: relative; padding: 0 1px; }
.elgg-sidebar-alt-river-activity .thumb { height: 20px; width: 7px; cursor: pointer; overflow: hidden; position: absolute; top: 0; }
.elgg-sidebar-alt-river-activity .thumb .end { overflow: hidden; height: 5px; width: 7px; }
.elgg-sidebar-alt-river-activity .disable{ display: none; }
.noSelect { user-select: none; -o-user-select: none; -moz-user-select: none; -khtml-user-select: none; -webkit-user-select: none; }

.more-block > ul.button:active > li.more-links {
	display: block;
	background: #fff;
}
.more-block > ul.button > li.more-links {
	z-index: 50;
	display: none;
	position: absolute;
}
.exec-content-url {
	color: #000;
}
li.elgg-menu-item-exec-content {
  float: right!important;
}

ul.elgg-menu-compound > li > a {
  font-weight: normal;
}

ul.elgg-menu-compound > li.elgg-state-selected > a {
  color: #000000;
  font-weight: bold;
}
.elgg-menu-compound {
	margin-top: 15px;
	padding-bottom: 1px;
}
.elgg-tabs.elgg-htabs {padding-top:5px}
.ui-icon, .text {
    display: inline-block;
    vertical-align: middle;
}
.ui-icon, .ui-widget-content .ui-icon {
    background-image: url('<?php echo elgg_get_site_url();?>/mod/gc_theme/views/default/images/themeroller-icon-set-vector-20px-2010-06-01.png');
    background-color: #fff;
}
.ui-icon-carat-1-e {
    background-position: -179px 0;
    margin-top: 2px;
    width: 10px;
    height: 20px;
    left: 3px;
}
.ui-icon-carat-1-w {
    background-position: -49px 0;
    margin-top: 2px;
    width: 10px;
    height: 20px;
    position: absolute;
    right: 3px;
    z-index: 9999;
}
.elgg-menu-dfait-adsync {
    padding-top: 7px;
}
li[class*="elgg-menu-item-user-"].ui-state-active > a {
  background: #fff;
}
li[class*="elgg-menu-item-user-"] {
  margin: 0 4px 0;
  padding: 0;
}

.section-controls-cont .elgg-menu-title {float:none;}
.elgg-menu-item-groups-invite,#event-manager-file-upload {float:right;}

.elgg-sidebar {
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
.elgg-sidebar {
	font-size: .85em;
}
.elgg-sidebar > h2{
	color: #666;
	/* font-size: 11px;*/
	padding: 5px 10px;
	text-transform: uppercase;
}
.dropdown.elgg-menu-page {
	border-bottom: 0;
}
.dropdown #elgg-expandable.elgg-menu-parent {
	position: relative;
	right: 0;
}
.dropdown > i {
	margin-top:-1.65em;
}
#elgg-expandable.elgg-menu-parent {
	display: block;
	width: 16px;
	height: 24px;
	overflow: hidden;
	position: absolute;
	right: 5px;
	top: 0;
	z-index: 10;
	background: url(<?php echo elgg_get_site_url(); ?>mod/gc_theme/views/default/images/menu-toggle-indicator.png) no-repeat 0 0;
	cursor: pointer;
}
.invitations-exist #elgg-expandable.elgg-menu-parent {
	background: url(<?php echo elgg_get_site_url(); ?>mod/gc_theme/views/default/images/menu-toggle-indicator-alert.png) no-repeat 0 0;
}
#elgg-expandable.elgg-menu-parent.elgg-menu-opened {
	background-position: 0 -24px;
}
.elgg-menu-page li.elgg-state-selected > a {
	background-color: #e7eef6;
	text-decoration: none;
}
.elgg-menu.elgg-child-menu.elgg-child-menu-opened {
	display: block;
}
.elgg-sidebar li.elgg-state-selected > ul {
	display: block;
}

.elgg-menu-page li > a {
	color: #1c4c8c;
	display: block;
	margin-bottom: 1px;
	padding: 3px 8px 3px 25px;
}
/* old agora */


/* ***************************************
	PAGINATION
*************************************** */
.elgg-menu-compound > li > a:hover,
.elgg-menu-compound > li > a,
.elgg-menu > li > a:hover,
.elgg-menu > li > a {
	text-decoration:none!important;
}
.elgg-menu > li > a {
	overflow: hidden;
	text-overflow: ellipsis;
	white-space: nowrap;
}

/* These menus always make room for icons: */
.elgg-menu-owner-block li > a > .elgg-icon,
.elgg-menu-extras li > a > .elgg-icon,
.elgg-menu-page li > a > .elgg-icon,
.elgg-menu-composer li > a > .elgg-icon {
	margin-left: -20px;
	margin-right: 4px;
}

.elgg-pagination>a {
	font-weight: bold;
}
.elgg-pagination {
	background: #F7F7F7;
	background-color: rgb(235, 241, 247);
	border: 1px solid #CCC;
	border-width: 1px 0;
	display: block;
	padding: 5px 0 5px 0;
	text-align: center;
}

.elgg-pagination > li {
	display:inline-block;
}

.elgg-pagination > li > a, 
.elgg-pagination > li > span {
	font-size: 13px;
	font-weight: bold;
	margin: 3px 11px 0 0;
	padding: 3px 4px 4px;
	text-align: center;
	display:block;
}
.elgg-pagination > li > a:hover {
	background: #3B5998;
	color: white;
	text-decoration: none;
}

.elgg-pagination > .elgg-state-selected > span {
	border-bottom: 2px solid #3B5998
}

/* ***************************************
	TABS
*************************************** */
.elgg-tabs {
	border-bottom: 1px solid #D8DFEA;
	display: block;
	width: 100%;
	/*padding-left:15px;*/
}

.elgg-tabs > li {
	margin: 2px 2px -1px 0;
	display: inline-block;
	background: #D8DFEA;
	border: 1px solid #D8DFEA;
	border-bottom: 0;
}

.elgg-tabs > :hover {
	background: #627AAD;
	border: 1px solid #627AAD;
	border-bottom: 0;
}

.elgg-tabs > li > a {
	font-size: 13px;
	font-weight: bold;
	text-align: center;
	padding: 3px 11px 4px;
	display:block;
}
.elgg-tabs > :hover > a {
	color: white;
	text-decoration:none;
}

.elgg-tabs > .elgg-state-selected {
	border: 1px solid #D8DFEA;
	border-bottom: 0;
	margin-top: 0;
}

.elgg-tabs > .elgg-state-selected > a,
.elgg-tabs > .elgg-state-selected:hover > a {
	background: white;
	color: #333;
	padding: 5px 10px 4px
}

/* ***************************************
	BREADCRUMBS
*************************************** */
.elgg-breadcrumbs {
	font-size: 80%;
	font-weight: bold;
	line-height: 1.2em;
	color: #bababa;
}
.elgg-breadcrumbs > li {
	display: inline-block;
}
.elgg-breadcrumbs > li:after{
	content: "\003E";
	padding: 0 4px;
	font-weight: normal;
}
.elgg-breadcrumbs > li > a {
	display: inline-block;
	color: #999;
}
.elgg-breadcrumbs > li > a:hover {
	color: #0054a7;
	text-decoration: underline;
}

/* ***************************************
	TOPBAR MENU
*************************************** */
.elgg-menu-topbar {
	float: left;
}

.elgg-menu-topbar > li {
	float:left;
	position: relative;
}

.elgg-menu-topbar > li > a {
/* 	color: white; */
 	color: black;
	display: block;
	font-weight: bold;
	height: 22px;
}

.elgg-menu-topbar-default > li > a {
	padding: 8px 4px 0;
	margin: 0 1px;
}

.elgg-menu-topbar-default > li > a:hover {
	background: #4B67A1;
}

.elgg-menu-topbar-alt {
	float:right;
	margin-right:1px;
}
.elgg-menu-topbar-alt > li > a {
	padding: 8px 10px 0;
}
.elgg-menu-topbar-alt > li > a:hover {
	background: #6D86B7;
}
/*
.elgg-menu-topbar .elgg-menu-parent:after {
	content: " \25BC ";
	font-size: smaller;
}
*/
.elgg-menu-topbar .elgg-child-menu {
	background: white;
	border: 1px solid #333;
	border-bottom: 2px solid #2D4486;
	margin-right: -1px;
	margin-top: -1px;
	min-width: 200px;
	padding: 10px 0 5px;
	position: absolute;
	right: 0;
	top: 100%;
	display:none;
	z-index:1000;
}

.elgg-menu-topbar .elgg-child-menu.elgg-state-active {
	display: block;
}

.elgg-menu-topbar .elgg-child-menu > li > a {
	color: #3A579A;
	display: block;
	font-weight: normal;
	height: auto;
	padding: 4px 10px 5px;
	white-space: nowrap;
}

.elgg-menu-topbar .elgg-child-menu > li > a:hover {
	background: #6D84B4;
	border-bottom: 1px solid #3B5998;
	border-top: 1px solid #3B5998;
	color: white;
	padding: 3px 10px 4px;
	text-decoration: none;
}

.elgg-menu-topbar > li > .elgg-menu-opened,
.elgg-menu-topbar > li > .elgg-menu-opened:hover {
	background: white;
	border: 1px solid #333;
	border-bottom: 0;
	margin: -1px -1px 0;
	color: #333;
	position:relative;
	z-index: 2;
}

/* ***************************************
	SITE MENU
*************************************** */
.elgg-menu-site:after {
	content: '.';
	clear:both;
	display:block;
	height:0;
	line-height:0;
}
.elgg-menu-site {
	background: #ECEFF5;
}
.elgg-menu-site > li {
	float: left;
}

.elgg-menu-site > li > a {
	padding: 8px 10px;
}

.elgg-menu-site > li > a:hover {
	background: white;
}

/* ***************************************
	TITLE
*************************************** */
.elgg-menu-title {
	float: right;
}

.elgg-menu-title > li {
	display: inline-block;
	margin-left: 4px;
}

/* ***************************************
	FILTER MENU
*************************************** */

#elgg-menu-group-filter-container {
	height: 48px;
	overflow:hidden;
	position:relative;
}
/*#elgg-menu-group-filter-container { position:relative;overflow-x:scroll;overflow-y:hidden}*/
.elgg-menu-group-filter {
	width: 140%;
}
/*
.elgg-menu-filter {
	margin-top: 20px;
}
*/
.elgg-menu-group-filter {
	margin-top: 10px;
}
.elgg-menu-filter, .elgg-menu-group-filter {
	display: block;	
	height: 25px;
}
.elgg-menu-filter:after, .elgg-menu-group-filter:after {
	content: '.';
	display:block;
	clear:both;
	visibility:hidden;
	height:0;
	line-height:0;
}

.elgg-menu-group-filter > li {
	margin: 0 -1px 0 7px;
	height: 24px;
	
}
.elgg-menu-filter > li {
	margin: 0 -1px -1px 0;
	height: 25px;
}
.elgg-menu-filter > li, .elgg-menu-group-filter > li {
	float: left;
}
.elgg-menu-filter > li.elgg-state-selected:after {
content: '';
width: 0;
height: 0;
border-style: solid;
border-width: 0 5px 8px 5px;
border-color: transparent transparent #ffffff transparent;
margin-left: -5px;
position: absolute;
bottom: 0;
left: 50%;
z-index: 1;
}

.elgg-menu-filter > li.elgg-state-selected > a {
	/*background: #FFF;*/
	font-weight:bold;
	color: #333;
}
.elgg-menu-group-filter > li.elgg-state-selected > a {
	background: #FFF;
}
.elgg-menu-group-filter > li > a {
	padding: 0 8px 3px 9px;
	height: 19px;
}
.group-list-item {
	padding: 0 7px 3px 9px!important;
}
.elgg-menu-filter > li > a {
	padding: 0 8px 3px 9px;
}
.elgg-menu-filter > li > a, .elgg-menu-group-filter > li > a {
	display: block;
	text-align: center;
	color: #1c4c8c;
	border-top: 2px solid #f4f4f4;
}

.elgg-menu-group-filter > .elgg-state-selected {
	background: #FFF;
}
.elgg-menu-filter, .elgg-menu-group-filter .elgg-state-selected a {
	background: #e7eef6;
}

/* ***************************************
	PAGE MENU
*************************************** */
.elgg-menu-page {
	border-bottom: 1px solid #EEE;
	margin-bottom: 7px;
	padding-bottom: 7px;
}
.elgg-menu-page li > a:hover {
	background-color: #EFF2F7;
}
.elgg-menu-page .elgg-child-menu {
	display: none;
	/*margin-left: 15px;*/
}
/* new agora
.elgg-menu-page .elgg-menu-closed:before, 
.elgg-menu-page .elgg-menu-opened:before {
	display: inline-block;
	padding-right: 4px;
}
.elgg-menu-page .elgg-menu-closed:before {
	content: "\002B";
}
.elgg-menu-page .elgg-menu-opened:before {
	content: "\002D";
}
*/

/* ***************************************
	HOVER MENU
*************************************** */
.elgg-menu-hover {
	display: none;
	position: absolute;
	z-index: 10000;

	width: 165px;
	border: solid 1px;
	border-color: #E5E5E5 #999 #999 #E5E5E5;
	background-color: #FFF;

	-webkit-box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.50);
	-moz-box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.50);
	box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.50);
}
.elgg-menu-hover > li {
	border-bottom: 1px solid #ddd;
}
.elgg-menu-hover > li:last-child {
	border-bottom: none;
}
.elgg-menu-hover .elgg-heading-basic {
	display: block;
}
.elgg-menu-hover a {
	padding: 2px 8px;
	font-size: 92%;
}
.elgg-menu-hover a:hover {
	background: #ccc;
}
.elgg-menu-hover-admin a {
	color: red;
}
.elgg-menu-hover-admin a:hover {
	color: white;
	background-color: red;
}

/* ***************************************
	FOOTER
*************************************** */
.elgg-menu-footer > li,
.elgg-menu-footer > li > a {
	color:#999;
	display: inline-block;
}

.elgg-menu-footer > li:after {
	content: " \00B7 ";
	padding: 0 4px;
}

.elgg-menu-footer-default {
	float:right;
}

.elgg-menu-footer-alt {
	float: left;
}

/* ***************************************
	ENTITY
*************************************** */
.elgg-menu-entity {
	float: right;
	margin-left: 15px;
	font-size: 90%;
	color: #aaa;
}
.elgg-menu-entity > li {
	display: inline-block;
	margin-left: 15px;
}
.elgg-menu-entity > li > a {
	color: #aaa;
}

/* ***************************************
	OWNER BLOCK
*************************************** */


.elgg-menu-owner-block li > a {
	border-bottom: 1px solid #D8DFEA;
	color: #333333;
	padding: 3px 8px 3px 26px;
}
.elgg-menu-owner-block li > a:hover {
	background-color: #3B5998;
	color: white;
}
.elgg-menu-owner-block .elgg-state-selected > a {
	background-color: #D8DFEA;
}

.elgg-menu-owner-block .elgg-menu > li > a {
	padding-left: 44px;
}

/* ***************************************
	LONGTEXT
*************************************** */
.elgg-menu-longtext {
	float: right;
	display: inline-block;
}

/* ***************************************
	RIVER
*************************************** */
.elgg-menu-river {
	color: #888;
	display: inline-block;
	margin: 3px 0 0 -3px;
}

.elgg-menu-river > li {
	display: inline;
}

.elgg-menu-river > li:before {
	content: " \00B7 ";
	display: inline-block;
	margin: 0 3px;
}

.elgg-menu-river > li > a {
	color: #6D84B4;
	display: inline;
}

.elgg-menu-river > li > a:hover {
	text-decoration: underline;
}

/* ***************************************
	SIDEBAR EXTRAS (rss, bookmark, etc)
*************************************** */
.elgg-menu-extras > li > a {
	padding: 3px 8px 3px 26px;
}

.elgg-menu-extras > li > a:hover {
	text-decoration:underline;
}

/* ***************************************
    COMPOSER
*************************************** */
.elgg-menu-composer {
	display:inline-block;
	height: 22px;
}

.elgg-menu-composer > li {
	font-weight:bold;
	margin-left: 10px;
}

.elgg-menu-composer > li > a {
	line-height: 16px;
	padding-left: 20px;
}

.elgg-menu-composer > li > a:hover {
	text-decoration: underline;
}

.elgg-menu-composer > li.ui-state-active > a {
	cursor: default;
	color: black;
	text-decoration: none;
}

.elgg-menu-compound > .elgg-state-selected > a:before,
.elgg-menu-compound > .elgg-state-selected > a:after,
.elgg-menu-composer > .ui-state-active > a:before,
.elgg-menu-composer > .ui-state-active > a:after {
	position: absolute;
	display: block;
	border-width: 8px;
	border-style: solid;
	content: " ";
	height: 0;
	width: 0;
	left: 50px;
}

.elgg-menu-compound > .elgg-state-selected > a:before,
.elgg-menu-composer > .ui-state-active > a:before {
	top: 11px;
	border-color: transparent transparent #B4BBCD transparent;
}

.elgg-menu-compound > .elgg-state-selected > a:after,
.elgg-menu-composer > .ui-state-active > a:after {
	top: 12px;
	border-color: transparent transparent white transparent;
}
