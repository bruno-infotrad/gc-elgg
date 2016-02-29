<?php
/**
 * CSS buttons
 *
 * @package Elgg.Core
 * @subpackage UI
 */
?>
/* **************************
	BUTTONS
************************** */
#export-members {
	float: right;
	position: relative;
	padding-right: 5px;
	text-decoration: none;
	top: -3.2em;
	
}
#file-tools-show-more-wrapper, #file-tools-show-all-wrapper { display: inline-block; }
#file-tools-show-more-wrapper { padding-left: 1px; }
#file-tools-show-all-wrapper { padding-left: 10px; }
#uploadify-button-wrapper {opacity:0;}
.event_manager_event_actions {
	background: url(http://192.168.239.130/newelgg-1.8.16/mod/event_manager/_graphics/arrows_down.png) right center no-repeat, linear-gradient(#fefefe, #dcdcdc);
	border: 1px solid;
	border-color: #ccc;
	border-radius: 4px;
	padding: 3px 15px 3px 3px;
}
#search-submit-button {
	background: rgb(238,238,238);
}
.gc_theme-intro .elgg-button-fancybox {
	border-radius: 4px;
	font-size: 9px;
	font-weight: normal;
	padding: 5px 15px;
	-webkit-border-radius: 4px;
}
.gc_theme-intro .elgg-button-fancybox,
.gc_theme-intro .elgg-button-join,
a.elgg-button-join {
	background-color: #176ca7;
	border-color: #0e4164 #0e4164 #0b324d;
	color: white!important;
	text-shadow: 0 1px 1px #222;
	background-image: url('data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgi…pZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZ3JhZCkiIC8+PC9zdmc+IA==');
	background-size: 100%;
	background-image: -webkit-gradient(linear,50% 0,50% 100%,color-stop(0%,#176ca7),color-stop(100%,#114f7a));
	background-image: -webkit-linear-gradient(#176ca7,#114f7a);
	background-image: -moz-linear-gradient(#176ca7,#114f7a);
	background-image: -o-linear-gradient(#176ca7,#114f7a);
	background-image: linear-gradient(#176ca7,#114f7a);
}
.gc_theme-intro .elgg-button-join,
a.elgg-button-join,a.elgg-button-leave {
  color: #000;
  text-decoration: none;
  font-size: 9px;
  font-weight: normal;
  padding: 5px 15px;
  border-radius: 4px;
  -webkit-border-radius: 4px;
}

.language-button-text {
	font-size: 13px;
	text-indent: 0px;
}
.button.button-icon.icon-settings {
	margin-right: -3px;
}
/*
.button.button-icon.language-settings {
	border-left: 0px;
	border-bottom-left-radius: 0px;
	border-top-left-radius: 0px;
	margin-left: -1.5em;
	padding-left: 0;
}
*/
.group-container-search-button {
	vertical-align: baseline;
	height: 31px;
	margin-right:0;
	margin-left:4px;
}
.site-notifications-buttonbank {text-align: left!important}
.site-notifications-buttonbank .elgg-button-submit,
.messages-buttonbank .elgg-button-submit {
    float: none;
}
.site-notifications-buttonbank .elgg-button-delete,
.messages-buttonbank .elgg-button-delete {
    /*margin-right: 65%;*/
}
.site-notifications-buttonbank .elgg-button,
.messages-buttonbank .elgg-button {
    display: inline-block;
}
.elgg-button + .elgg-button {
	margin-left: 4px;
}

/* Base */
.elgg-button {
	color: #333;
	font-weight: bold;
	text-decoration: none;
	width: auto;
	margin: 0;
	font-size: 11px;
	line-height: 16px;
	
	padding: 2px 6px;
	cursor: pointer;
	outline: none;
	text-align: center;
	white-space: nowrap;

	-webkit-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.10), inset 0 1px 0 #fff;
	-moz-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.10), inset 0 1px 0 #fff;
	box-shadow: 0 1px 0 rgba(0, 0, 0, 0.10), inset 0 1px 0 #fff;

	border: 1px solid #999;
	border-bottom-color: #888;

    background: #eee;
    background: -webkit-gradient(linear, 0 0, 0 100%, from(#f5f6f6), to(#e4e4e3));
    background: -moz-linear-gradient(#f5f6f6, #e4e4e3);
    background: -o-linear-gradient(#f5f6f6, #e4e4e3);
    background: linear-gradient(#f5f6f6, #e4e4e3);
}
.elgg-menu .elgg-button { 
  background-image: -webkit-gradient(linear, 50% 0%, 50% 100%, color-stop(0%, #fefefe), color-stop(100%, #dcdcdc));
  background-image: -webkit-linear-gradient(#fefefe, #dcdcdc);
  background-image: -moz-linear-gradient(#fefefe, #dcdcdc);
  background-image: -o-linear-gradient(#fefefe, #dcdcdc);
  background-image: linear-gradient(#fefefe, #dcdcdc);
  border-color: #ccc;
  font-weight: normal;
  padding: 5px 15px;
  border-radius: 4px;
  -webkit-border-radius: 4px;
}

#gc_theme-cancel-button,
a#thewire-contribute-to {
  font-weight: normal;
  text-align: center;
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 13px;
}
#gc_theme-cancel-button { float: left; margin-left: 0}
#thewire-contribute-to { float: right;}
#gc_theme-cancel-button,
#thewire-contribute-to {
	border-radius: 4px;
	color: #000!important;
	margin-right: 10px;
	margin-top: -2px;
	border-color: #ccc;
	background-color: rgb(238, 238, 238);
	background-image: -webkit-gradient(linear, 50% 0%, 50% 100%, color-stop(0%, #fefefe), color-stop(100%, #dcdcdc));
	background-image: -webkit-linear-gradient(#fefefe, #dcdcdc);
	background-image: -moz-linear-gradient(#fefefe, #dcdcdc);
	background-image: -o-linear-gradient(#fefefe, #dcdcdc);
	background-image: linear-gradient(#fefefe, #dcdcdc);
	text-shadow: none;
}
#thewire-contribute-to {
	pointer-events: none;
}

.gc_theme-submit-button {float: right;}
#thewire-cancel-button { float: left;}

[id*="gc-collection-save-"],
.gc_theme-submit-button,
#thewire-cancel-button {
	margin-top: -2px;
}

#thewire-submit-button {
	float: right;
	margin-top: -2px;
}

#select-contribute-to,
#thewire-contribute-to,
#thewire-submit-button:disabled {
	opacity: .5;
}

[id*="gc-collection-save-"],
[id*="gc-collection-cancel-"],
#gc_theme-cancel-button,
.gc_theme-cancel-button,
.gc_theme-submit-button,
#gft-cancel-button,
#gft-submit-button,
#thewire-cancel-button,
#thewire-submit-button {
  font-weight: normal;
  text-align: center;
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 13px;
}

a#select-contribute-to {margin-left: 36%;}

.pvs .elgg-requires-confirmation  {
  background-color: #000;
  border-radius: 4px;
}

#event-manager-file-upload, .elgg-menu-item-addfriend .elgg-button,.elgg-menu-item-editprofile .elgg-button, .elgg-menu-item-editavatar .elgg-button, .pvs .elgg-button-submit  {
  background-color: #176ca7;
  background-image: url('data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgi…pZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZ3JhZCkiIC8+PC9zdmc+IA==');
  background-size: 100%;
  background-image: -webkit-gradient(linear,50% 0,50% 100%,color-stop(0%,#176ca7),color-stop(100%,#114f7a));
  background-image: -webkit-linear-gradient(#176ca7,#114f7a);
  background-image: -moz-linear-gradient(#176ca7,#114f7a);
  background-image: -o-linear-gradient(#176ca7,#114f7a);
  background-image: linear-gradient(#176ca7,#114f7a)
  border-color: #0e4164 #0e4164 #0b324d;
  border-radius: 4px;
  color: white!important;
  text-shadow: 0 1px 1px #222;
}

[id*="gc-collection-save-"] {
  background-image: url('data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgi…pZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZ3JhZCkiIC8+PC9zdmc+IA==')!important;
}
[id*="gc-collection-save-"],
.gc_theme-submit-button,
#gft-cancel-button,
#gft-submit-button,
#select-pns,
a#select-contribute-to,
#message-friendsof,
#new-colleague-collection,
#thewire-submit-button,
#event-manager-file-upload,
.elgg-menu-item-groups-invite .elgg-button { 
  background-color: #176ca7;
  border-color: #0e4164 #0e4164 #0b324d;
  color: white!important;
  text-shadow: 0 1px 1px #222;
  background-image: url('data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgi…pZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZ3JhZCkiIC8+PC9zdmc+IA==');
  background-size: 100%;
  background-image: -webkit-gradient(linear,50% 0,50% 100%,color-stop(0%,#176ca7),color-stop(100%,#114f7a));
  background-image: -webkit-linear-gradient(#176ca7,#114f7a);
  background-image: -moz-linear-gradient(#176ca7,#114f7a);
  background-image: -o-linear-gradient(#176ca7,#114f7a);
  background-image: linear-gradient(#176ca7,#114f7a);
}

.elgg-button:hover {
	color:#333;
	text-decoration:none;
}

.elgg-button:active {
	background: #ddd;
	border-bottom-color:#999;
	
	box-shadow: none;
	-webkit-box-shadow: none;
	-moz-box-shadow: none;
}

.elgg-button.elgg-state-disabled {
	background: #F2F2F2;
	border-color: #C8C8C8;
	color: #B8B8B8;
	cursor: default;
	
	box-shadow: none;
	-webkit-box-shadow: none;
	-moz-box-shadow: none;
}

/* Submit: This button should convey, "you're about to take some definitive action" */
.elgg-button-submit {
	color: #fff !important;
    background: #5B74A8;
    background: -webkit-gradient(linear, 0 0, 0 100%, from(#637bad), to(#5872a7));
    background: -moz-linear-gradient(#637bad, #5872a7);
    background: -o-linear-gradient(#637bad, #5872a7);
    background: linear-gradient(#637bad, #5872a7);
	border-color: #29447E #29447E #1A356E;
	-webkit-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.10), inset 0 1px 0 #8a9cc2;
	-moz-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.10), inset 0 1px 0 #8a9cc2;
	box-shadow: 0 1px 0 rgba(0, 0, 0, 0.10), inset 0 1px 0 #8a9cc2;
    float:left;
	
}

.elgg-button-submit:active {
	background: #4f6aa3;
	border-bottom-color: #29447e;
}

.elgg-button-submit.elgg-state-disabled {
	background: #ADBAD4;
	border-color: #94A2BF;
}


/* Delete: This button should convey "be careful before you click me" */
.elgg-button-delete {
	background: #444;
	border: 1px solid #333;
	color: #eee !important;
	-webkit-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.10), inset 0 1px 0 #999;
	-moz-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.10), inset 0 1px 0 #999;
	box-shadow: 0 1px 0 rgba(0, 0, 0, 0.10), inset 0 1px 0 #999;
}

.elgg-button-delete:active {
	background: #111;
}

.elgg-button-delete.elgg-state-disabled {
	background: #999;
	border-color: #888;
}

/* Special: This button should convey "please click me!" */
.elgg-button-special {
	color:white !important;
    background: #69a74e;
    background: -webkit-gradient(linear, 0 0, 0 100%, from(#75ae5c), to(#67a54b));
    background: -moz-linear-gradient(#75ae5c, #67a54b);
    background: -o-linear-gradient(#75ae5c, #67a54b);
    background: linear-gradient(#75ae5c, #67a54b);
	border-color: #3b6e22 #3b6e22 #2c5115;
	-webkit-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.10), inset 0 1px 0 #98c286;
	-moz-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.10), inset 0 1px 0 #98c286;
	box-shadow: 0 1px 0 rgba(0, 0, 0, 0.10), inset 0 1px 0 #98c286;
}

.elgg-button-special:active {
	background:#609946;
	border-bottom-color:#3b6e22;
}

.elgg-button-special.elgg-state-disabled {
	background: #B4D3A7;
	border-color: #9DB791;
}

/* Other button modifiers */
.elgg-button-dropdown {
	color: white;
	border:1px solid #71B9F7;
}

.elgg-button-dropdown:after {
	content: " \25BC ";
	font-size: smaller;
}

.elgg-button-dropdown:hover {
	background-color:#71B9F7;
}

.elgg-button-dropdown.elgg-state-active {
	background: #ccc;
	color: #333;
	border:1px solid #ccc;
}

.elgg-button-large {
	font-size: 13px;
	line-height: 19px;
}
