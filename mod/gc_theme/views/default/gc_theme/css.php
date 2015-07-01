<?php 
/**
 * Fixes/tweaks
 */

?>
/*.elgg-form-groups-search .elgg-input-checkbox {margin-left:5%}*/
#remember-me {display:inline-block;}
li.elgg-menu-item-groups-all {padding-left: 0!important;}
li.elgg-menu-item-groups-add {padding-bottom: 7px!important;}
li[class*='elgg-menu-item-groups-'],
li[class*='elgg-menu-item-group-'] {
	padding-left: 17px;
}
/*.elgg-menu-item-groups-user-invites,.elgg-menu-item-groups-add,.elgg-menu-item-groups-yours-more,.elgg-menu-item-groups-invitations,.elgg-menu-item-groups-join-request,*/
.elgg-menu-item-wire,
li[class*='elgg-menu-item-friends-'],.elgg-menu-item-multi-invite, .elgg-menu-item-messages-sentmessages {
	padding-left: 20px;
}

.elgg-body-walledgarden {display:none;}

#cn-collab-banner-text,#cn-collab-banner-subtext {
	color: #FFFFFF;
	font-family: Arial,Helvetica,sans-serif;
        font-weight: bold;
	text-align: left;
	text-shadow: 1px 1px 1px #333333;
}

#cn-collab-banner-text {
	padding: 60px 130px 0;
	font-size: 2.00em;
}

#cn-collab-banner-subtext {
	padding: 0px 130px 0!important;
	font-size: 1.60em;
}

#multi_invite_users {
        margin-bottom: 10px;
}

#multi_invite_users .multi_invite_autocomplete_result {
        border: 1px solid transparent;
}

#multi_invite_users .multi_invite_autocomplete_result img {
        vertical-align: middle;
}

#multi_invite_users .multi_invite_autocomplete_result .elgg-icon {
        vertical-align: text-top;
        margin-left: 10px;
        display: none;
        cursor: pointer;
}

#multi_invite_users .multi_invite_autocomplete_result:hover {
        border-color: #cecece;
}

#multi_invite_users .multi_invite_autocomplete_result:hover .elgg-icon {
        display: inline-block;
}

#multi_invite_autocomplete_autocomplete_results {
        margin-top: 30px;
}
p.namefieldlink {
	float:right;
        margin:8px 10px 0 0!important;
}
.elgg-menu-item-group-admin {
	padding-left: 0px!important;
}
.elgg-form.elgg-form-compound-add {
	clear: both;
}

.iewarning {
        background-color: #ff8800;
}
.elgg-composer.elgg-composer-dashboard {
	border-top-style: none;
	border-top-width: 0px;
	clear: both;
}
#elgg-river-selector {
	float: right;
}
.elgg-module-aside > .elgg-head-onlineusers {
        background-color: #F2F2F2;
        border-bottom: none;
        border-top: solid 1px #E2E2E2;
	clear: left;
        padding: 4px 5px 5px;
        margin-bottom: 5px;
}       

.elgg-module-aside > .elgg-head-onlineusers > h3 {
        font-size: 1em;
}    
.ui-autocomplete.ui-menu.ui-widget.ui-widget-content.ui-corner-all {
	width: 474px;
	background-color: #ffffff;
}
.motd {
        background-color: #CCCCCC;
}
.motd {
	border

        -webkit-border-radius: 10px;
        -moz-border-radius: 10px;
        border-radius: 10px;

        -webkit-box-shadow: -2px 2px 4px rgba(0, 0, 0, 0.50);
        -moz-box-shadow: -2px 2px 4px rgba(0, 0, 0, 0.50);
        box-shadow: -2px 2px 4px rgba(0, 0, 0, 0.50);
	padding-right: 5px;
	padding-left: 5px;
	margin-top: 5px;
	margin-bottom: 5px;
	padding-top: 5px;
	padding-bottom: 5px;

}

#name_survey {
        text-align: center;
	margin-top: 10px;
}

.whatis > ul > li {
        text-align: justify;
}

.whatis > h4 {
        text-align: center;
}

.whatis {
        background-color: #CCCCCC;
	border

        -webkit-border-radius: 10px;
        -moz-border-radius: 10px;
        border-radius: 10px;

        -webkit-box-shadow: -2px 2px 4px rgba(0, 0, 0, 0.50);
        -moz-box-shadow: -2px 2px 4px rgba(0, 0, 0, 0.50);
        box-shadow: -2px 2px 4px rgba(0, 0, 0, 0.50);
	margin-right: 7px;
	padding-right: 5px;
	padding-left: 5px;
	padding-top: 5px;
	padding-bottom: 5px;

}

.elgg-sidebar,
.elgg-sidebar-alt-river,
.elgg-body {
	display: inline-block;
	vertical-align: top;
	zoom: 1;
}
.elgg-sidebar-alt-river {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	margin-right:-0.4em;
	margin-left:-0.4em;
}
.elgg-sidebar-alt-river > h2,
.elgg-sidebar-alt-river > .elgg-module > h2,
.elgg-sidebar-alt-river-activity  h4,
.elgg-sidebar-alt-river-activity > h4 {
	color: #999;
	font-size: 1.1em;
	font-weight: bold;
	margin: 0 0 1em;
	padding: 10px 0 5px 10px;
	text-transform: uppercase;
}
.elgg-layout-two-sidebar > .elgg-body {
	min-height: 57em;
	padding-left: 0px;
	padding-right: 0px;
}

/* new message format message moved to sidebar */
.gc-messages,
.gc-messages-new {
        color: red;
/*
        background-color: red;

        -webkit-border-radius: 10px;
        -moz-border-radius: 10px;
        border-radius: 10px;

        -webkit-box-shadow: -2px 2px 4px rgba(0, 0, 0, 0.50);
        -moz-box-shadow: -2px 2px 4px rgba(0, 0, 0, 0.50);
        box-shadow: -2px 2px 4px rgba(0, 0, 0, 0.50);

        text-align: center;
        top: 0px;
        left: 26px;
        height: 16px;
        font-size: 10px;
        font-weight: bold;
*/
padding: 2px;
border: 1px solid #ccc;
font-size: 9px;
line-height: 1em;
background: #fff;
-webkit-border-radius: 2px;
-moz-border-radius: 2px;
-ms-border-radius: 2px;
-o-border-radius: 2px;
border-radius: 2px;
margin-left: 5px;
display: inline-block;
}
.gc-messages-new {
display: none;
}

.elgg-sidebar .elgg-new-feeds {
	color:black;
}
#gc-group-invitations-list {float: right;}
#gc-group-invitations-list>a{color:red;font-weight:bold;}
#gc-group-invitations,
.elgg-new-feeds {
	color:red;
	font-weight:bold;
}
.elgg-tagcloud {
	min-height:350px;
}

.elgg-sidebar-alt-settings {
	padding-left: 20px;
	width: auto;
}

.elgg-form-variable {
	height: 35px;
}

/* group wall format */
#elgg-group-brief-description {
	width:400px;
}
#elgg-group-description {
	padding-top:10px;
	padding-bottom:5px;
}

/* fix sidebar_alt for group wall */
.elgg-head-group {
	min-height: 105px;
}
/* white body, grey outside */
.cn-body-inner-3col {
	background: #ffffff;
}
#cn-body-inner-3col {
	background: #ffffff;
}
/* profile items and avatars */
#elgg-profile-items {
	float: left;
	width: 70%;
	min-height:300px;
	/*padding-top: 10px;*/
}
#elgg-profile-avatar {
	float: right;
	padding-top: 10px;
	width: auto;
}
/* updated items */
.updated-item {
	background-color:grey;
	font-weight: bold;
	padding:0!important;
	text-align: center;
}

.updated-annotation {
	border-color:white white white #ff6600;
	border-width:5px;
	border-style:solid;
	padding-left:5px
}
/* fix file browse
.elgg-input-file {
        height:25px;
	 width:35%;
}
*/

#upload-file {
	padding-left:10px;padding-right:10px;padding-top:5px;float:left;font-weight:bold;position:relative;
}

/* fix for common menu bars Firefox */
.fp {
	clear: both;
}

.elgg-icon {vertical-align:middle}
dl, dt, dd {margin:0;padding:0}

/* PROFILE */
.elgg-profile {
	display:block;
}

.elgg-profile > dt {
	color: #000;
	float: left;
	font-weight:bold;
	padding-top: 10px;
	width: 120px;
}
	
.elgg-profile > dd {
	padding: 10px 0 10px 130px;
}

.elgg-profile > dd ~ dd {
	border-top: 1px solid #E9E9E9;
}

.elgg-profile > dd + dd {
	padding-left: 0;
	margin-left: 120px;
}

.elgg-profile-longfield {
	clear: both;
	text-align: right;
}
#elgg-profile-items > h3{
	clear: both;
}

.elgg-profile-longfield > dt {
	float: left;
	width: 120px;
	font-weight:bold;
	color: #0000;
	padding: 10px 0;
}
	
.elgg-profile-longfield > dd {
	padding: 10px 0 10px 130px;
	text-align: left;
}

.elgg-profile-longfield > dd ~ dd {
	border-top: 1px solid #E9E9E9;
}

.elgg-profile-longfield > dd + dd {
	padding-left: 0;
	margin-left: 130px;
}

/*img {max-width:100%}*/

#groups-tools > .elgg-module {
	width: 229px;
}

#gc-topbar-logo {
	margin-top: -4px;
	font-size: 20px;
	color: blue;
	text-shadow: 0px 0px 1px lightBlue;
	width: 100px;
	text-align:center;
}

#gc-header-logo a {
	color: white;
	text-decoration:none;
	font-size: 2.5em;
}

.elgg-form-small input,
.elgg-form-small textarea {
	font-size: 11px;
}

.elgg-image-block-small > .elgg-image {
	margin-right: 5px;
}


/* NEW PAGE COMPONENT: COMPOSER */

.ui-tabs-hide {
	display:none;
}

.elgg-composer {
	border-top: 1px solid #CCC;
	padding-top: 6px;
	margin-top: 7px;
}

.elgg-composer > h4 {
	height: 22px;
	display: inline-block;
	vertical-align: baseline;
	color: gray;
}

.elgg-compound > .ui-tabs-panel,
.elgg-composer > .ui-tabs-panel {
	margin: 5px 20px 0;
	border: 1px solid #B4BBCD;
}

.mtm {
	margin: 0px;
}

.messageboard-input {
	margin-bottom: 5px;
}

.elgg-attachment-description {
	margin-top: 5px;
}

#thewire-form-composer #thewire-textarea {
	margin-top:0;
}

.messageboard-input {
	height: 60px;
}

#gc-header-login {
	right: 0;
	position: absolute;
	bottom: 15px;
}

#gc-header-login label {
	color:white;
	font-weight: normal;
	padding: 2px 2px 4px;
	display: block;
}

#gc-header-login input[type="submit"] + label {
	color: #98A9CA;
/*	position:absolute; */
	position:relative;
	left: 0;
	bottom: -3px;
	cursor: pointer;
}

#gc-header-login div {
	display: inline-block;
	padding-right: 10px;
	margin-bottom: 3px;
}

#gc-header-login .elgg-input-text,
#gc-header-login .elgg-input-password {
	padding: 3px 3px 4px;
	color: black;
	width: 150px;
	border-color: #1D2A5B;
	margin:0;
	font-size:11px;
}

#gc-header-login .elgg-menu {
	margin-left: 166px;
}

#gc-header-login .elgg-menu > li > a {
	color: #98A9CA;
}

#gc-header-login .elgg-menu > li > a:hover {
	text-decoration: underline;
}

input[type="checkbox"] {
	vertical-align:bottom;
}

// SR [2012-09-11] For the profile completeness.
#elgg-profile-completeness {
	float: right;
	padding-top: 10px;
	width: 200px;
	min-height: 0px;
}
#elgg-profile-gadgets {
	float: left;
	margin-left: 20%;
}
