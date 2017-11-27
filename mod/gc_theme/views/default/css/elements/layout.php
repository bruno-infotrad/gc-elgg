<?php
/**
 * Page Layout
 *
 * Contains CSS for the page shell and page layout
 *
 * Default layout: 990px wide, centered. Used in default page shell
 *
 */
?>
#elgg-dropzone-preview,.dz-file-preview,.dz-image,.dz-details,.dz-size,.dz-filename,.dz-progress,.dz-upload,.dz-error-message {
	display: inline-block;
}

#elgg-dropzone-preview, #elgg-dropzone-preview-multi, #elgg-dropzone-preview-embed {
    border-style: dashed;
    border-width: thin;
    margin-left: 5%;
    min-height: 40px;
    width: 90%;
}
#elgg-dropzone-preview-multi {
    margin-top: 10px;
}
.dz-message {
    font-weight: bold;
    margin-left: 27%;
    margin-top: 10px;
}
.dz-details,#dz-remove {
	font-size: 11px;
	padding-left: 1em;
	vertical-align: top;
}
.dz-preview {
    display: flex;
}

.dz-error-message {
	color: red;
	font-weight: bold;
}

.gc-file-name,
.gc-file-date-created {
	display: inline-block;
	font-size: 11px;
}
.gc-file-name {
	font-weight: bold;
	width: 35%;
}
.gc-file-date-created {
	padding-left: 3px;
	vertical-align: top;
	width: 11%;
}
.tag-total,
.tag-name {
	display: inline-block;
}
.tag-block {
	display: inline-block;
	min-width: 30%;
	padding: 0 5px;
}
.tag-total {
	width: 2em;
}
.tag-name {
    color: #39739D;
    font-size: 12px;
    white-space: nowrap;
    background: #E1ECF4 none repeat scroll 0% 0%;
    border: 1px solid #E1ECF4;
    display: inline-block;
    margin: 2px 2px 2px 0px;
    border-radius: 0px;
    transition: color 0.15s ease 0s, background 0.15s ease 0s, border 0.15s ease 0s;
}
.gc-multi-group-posts {display: none;}
.elgg-body.gc-notification {display: inline;}
.gc-river-event {display:inline-block;vertical-align:top;}
.gc-river-event-icon {padding-left:10px;}
#event-exec-content {float: right;padding-right: 1em;}
#registrationform-preamble {padding: 5px;}
#heure {display: inline-block; padding-right: 5px;}
#gc-file-event-selected { margin-left: 9em; top: -1.7em; position: relative;}
.event_manager_event_list_search_input {position: relative; top: -1.1em;}
#event-manager-search-button {display:inline-block;}
#event-manager-search-button>.elgg-button {height:28px;}
.gc-event_manager_registrationform_fields {padding: 1em}
.gc-event_manager_registrationform_fields.elgg-module-popup 
.gc-datepicker {width:96%;margin:1.2em 0.3em}
/*.gc-datepicker>#att-events {display:none}
.gc-datepicker>#my-events {display:none}*/
.gc-datepicker>#all-events>h4 {margin-left:45%}
.gc-datepicker>#att-events>h4 {margin-left:35%}
.gc-datepicker>#my-events>h4 {margin-left:35%}
.pvs .elgg-body {max-width:65%;}
li#intro-tell-us-join {padding-bottom: 5px;}
#gc_theme-avatar-upload .elgg-button-submit,
#gc_theme-intro-uploadavatar .elgg-button-submit {
	float: right;
	margin-right: 20%;
}
#gc_theme-avatar-upload .elgg-button-submit,
#gc_theme-avatar-upload .input-file,
#gc_theme-intro-uploadavatar .elgg-button-submit,
#gc_theme-intro-uploadavatar .input-file {
	display: inline-block;
}

#gc_theme-avatar-upload .elgg-image-block>.elgg-body {width: 70%;}
#gc_theme-intro-uploadavatar .elgg-image-block>.elgg-body {width: 50%;}
#gc_theme-intro-uploadavatar {height: 240px;width: 450px;}
#intro-join-group {width: 300px;}
#gc_theme-parent-folder-label {
	vertical-align: middle;
}
.gc-addfriend {width:8em !important;left:-0.3em !important;}
#profile-modify-block li.elgg-menu-item-editavatar, .admin-group-notifications {display: block;}
#profile-modify-block>h4 {margin-left: 1.5em;}
#profile-modify-block {
	border: 1px solid #DEDEDE;
	border-radius: 10px;
	max-width: 98px;
	min-height: 75px;
}
.profile-icon-block .elgg-button {
	left: -0.8em;
	padding: 2px 5px;
	position: relative;
	width: 6em;
}
.profile-icon-block>ul {
	float: none;
}
.profile-icon-block {
	display: inline-block;
}
.single-group-save>.elgg-body {
	padding: 5px;
}
.single-group-save {
	border-top: 1px solid #DEDEDE;
}
.gc_theme-intro {
	padding: 10px;
}
.gc_theme-intro > ol {
	list-style: decimal;
	margin-left: 2em;
}
.gc_theme-intro > ul  {
	list-style: disc;
	margin-left: 2em;
}
.gc_theme-intro  p  {
	display: inline-block;
	margin-bottom: 1em;
	margin-top: 1em;
}
.bandeau-exec-content-title {
	color: #000;
	font-weight: bold;
	margin: 5px 20px 5px 20px;
}
.bandeau-exec-content {
	border: 1px solid #B4BBCD;
	border-radius: 10px;
	-webkit-border-radius: 10px;
	margin: 5px 20px 5px 20px;
	overflow: hidden;
	padding: 5px;
}
.bandeau-exec-content > li.exec-content-box > .elgg-avatar {
	vertical-align: middle;
}
.bandeau-exec-content > li.exec-content-box {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
	list-style-type: none;
	word-break: break-all;
}
.title-box {padding: 15px 5px 10px 30px; overflow: hidden;}
.elgg-head>a {
	float: right;
}
.elgg-gallery > li[id*="elgg-user-"] {
    width: 22%;
    padding: 10px 5px;
    vertical-align: top;
}
/* ***************************************
	PAGE LAYOUT
*************************************** */
/***** DEFAULT LAYOUT ******/
.elgg-page-default .elgg-page-header > .elgg-inner {
	/* width: 990px; */
	margin: 0 auto;
	height: 90px;
}
.elgg-page-default .elgg-page-body > .elgg-inner {
	/*width: 1010px;*/
	margin: 0 auto;
	background: #FFF;
}

.elgg-page-footer {
	/* width: 990px; */
	margin: 0 auto;
}
.elgg-page-default .elgg-page-footer > .elgg-inner {
	margin-left: 200px;
	padding: 8px;
}

.elgg-page-footer > .elgg-inner:after {
	display:block;
	content: '.';
	clear:both;
	visibility:hidden;
	height:0;
}

/***** TOPBAR ******/
.elgg-page-topbar {
	/*  background: #3B5998; */
	background: #FFFFFF;
	/* min-width: 998px; */
	position: relative;
	height: 41px;
}

.elgg-page-topbar > .elgg-inner {
	/* width: 990px; */
	margin: 0 auto;
	padding-top: 11px;
	position:relative;
}

.elgg-page-topbar > .elgg-inner:before {
	position: absolute;
	display: block;
	/* background: #627AAD; */
	background: #FFFFFF;
	height: 30px;
	bottom: 0;
	right: 0;
	left: 200px;
	/* border: 1px solid #1D4088; */
	border: 1px solid #DEDEDE;
	content: " ";
	width: auto;
	border-bottom: 0;
	top: 10px;
}

/***** PAGE MESSAGES ******/
.elgg-system-messages {
	position: fixed;
	top: 24px;
	right: 20px;
	max-width: 500px;
	z-index: 1000;
}
.elgg-system-messages li {
	margin-top: 10px;
}
.elgg-system-messages li p {
	margin: 0;
}

/***** PAGE HEADER ******/
.elgg-page-header {
	position: relative;
/*	background: #3B5998; */
	background: #FFFFFF;
}
.elgg-page-header > .elgg-inner {
	position: relative;
}

/***** PAGE BODY LAYOUT ******/
.elgg-layout {
	min-height: 360px;
}

.elgg-layout-one-column {
	padding: 10px 0;
}

.elgg-sidebar {
	padding: 0;
	min-height: 360px;
}


.elgg-sidebar-online-friends  h1{
	font-size: 17px;
	font-weight: normal;
}

.elgg-group-stats {
	float: left;
	width: 48%;
}
.elgg-group-stats a {
	margin-top: 10px;
}

.elgg-group-members {
	float: left;
	margin-left: 4%;
	width: 48%;
	max-height: 200px;
	overflow: auto;
}

.elgg-main {
	position: relative;
	min-height: 360px;
}

.elgg-layout-two-sidebar > .elgg-body,
.elgg-layout-one-sidebar > .elgg-body {
	border: 1px solid #DEDEDE;
	border-top-style: none;
	border-top-width: 0px;
}

.elgg-layout > .elgg-body > .elgg-head {
	padding: 5px;
	margin-bottom: 10px;
}
.new-feed {
	width: 90%;
}
.elgg-sidebar-alt-river-activity {
	border-top: 1px solid #dedede;
	max-height: 22em;
	overflow: hidden;
}
#elgg-sidebar-alt-river > .elgg-image-block,
.elgg-module > .elgg-image-block{
	padding-left: 0.8em;
	padding-right: 0.8em;
	margin-bottom: 1em;
}
.elgg-body.sidebar-activity-item a {
	display: inline-block;
	-ms-word-break: break-all;
}
.elgg-body.sidebar-activity-item,
.elgg-body.sidebar-discussion-item {
	display: block;
	word-break: break-word;
	word-wrap: break-word;
}
.elgg-body.sidebar-activity-item {
	margin-left: 4.7em;
}
.elgg-body.sidebar-discussion-item {
	margin-left: 4.8em;
}
.elgg-module.elgg-module-aside#top-border {
	border-top: 1px solid #dedede;
}

/***** PAGE FOOTER ******/
.elgg-page-footer {
	position: relative;
	color: #999;
}
.elgg-page-footer a:hover {
	color: #666;
}
