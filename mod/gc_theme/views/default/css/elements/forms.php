<?php
?>
#elgg-input-group-tags-autocomplete-results {list-style-type: none;}
#elgg-input-group-tags-autocomplete-results li:hover {background-color: #e7eef6;}
[id*="gc-collection-rename-"] { display: inline-block;}
[id*="gc-collection-save-"] { display: inline-block;}
[id*="gc-collection-cancel-"] { display: inline-block;}
[id*="gc-collection-name-"] { display: inline-block;}
[id*="gc-collection-edit-rename-"] { display: none;}
td.event_manager_event_edit_label.gc-required {background-color: #e7eef6;}
#poll-submit-button,#polls_edit_cancel {display: inline-block;}
.poll-tag,.poll-title {margin-left:0.5em;width: 80%;}
.elgg-form-polls-edit label {display: inline-block;}
.elgg-form-polls-edit {margin: 5px;}
.poll-title-cont {display: inline-block;}
#file_tools_list_new_folder_toggle {
	margin-left: 20px;
}
#file-tools-folder-tree {
	margin-top: 20px;
}
#search-date {
	color: rgb(180,187,205);
}
.elgg-form-friends-name-search,
.elgg-form-members-name-search,
.elgg-form-groups-find {
  margin-top: 10px;
}
#formtoggle {display: none;}
#thewire-single {
  margin: 0px 5px 0;
  border: solid 1px;
  border-color: white rgb(180,187,205);
}
.messages-container .unread {
	font-weight: bold;
}
.messages-container {
  margin-top: 20px;
}
#elgg-input-searchgroup {
  width:78%;
  color: gray;
}
#page-body {display: inline-block;}
#thewire-exec-content {margin-left: 5%;}
.elgg-form-pages-edit,#blog-post-edit {padding: 0px 5px;}
#compound_poll,#compound_file {
	display: none;
}
label[for=blog_description] {
	display: inline-block;
}
#multi-upload-button {
  height: 28px;
}
#multi-upload-div {
  vertical-align: middle;
  height: 32px;
}
#file-tools-uploadify-cancel {height: 28px;}
#uploadify-button-wrapper.uploadify {top: -30px;}
.gc-file-selected {
  border: 1px solid #BDC7D8;
  border-radius: 3px;
  min-width: 15em;
  padding: 5px;
}
.gc-addtoriver-row {display:block;position:relative;top:-10px;}
.gc-addtoriver-cb {padding-left:10px;}
.gc-addtoriver-cb,.gc-input-file-ib {display:inline-block;}
.gc-input-file-queue {display:inline-block;margin-left:5em;vertical-align:top;}
.gc-input-file-row {display:inline-block;margin-top:5px;}
.gc-input-file-browse {display:inline-block;vertical-align:top;}
.gc-input-file-2em {display:inline-block;margin-left: 2em;}
.gc-input-file-1em {display:inline-block;margin-left: 1em;}
.gc-input-file-desc {display:inline-block;margin-left: 1em;vertical-align: text-top;}
.input-file {
	/*max-width: 17em;
	min-height: 1em;
	position:absolute;
	top: 1.7em;*/
	max-width: 6em;
	max-height: 30px;
	padding-left: 5px;
}
.elgg-button.elgg-file-button {
	border-radius: 4px;
	cursor: pointer;
	/*margin-left: 10em;*/
	min-height: 1.8em;
	min-width: 6.5em;
	padding-top: .5em;
	text-align: center;
	vertical-align: top;
	width: auto;
}
.elgg-input-file {
	/*margin-left: 8.5em;
	min-height: 1.8em!important;*/
	/*left: -7em;*/
	top: -25px;
	min-height: 30px!important;
	opacity: 0;
	padding: 0;
	position:relative;
	width: 6.5em;
}

/* ***************************************
	Form Elements
*************************************** */
fieldset > div {
	margin-bottom: 5px;
}
fieldset > div:last-child {
	margin-bottom: 0;
}

label {
	font-weight: bold;
}

input, textarea {
	font-family: "Lucida Grande", Tahoma, Verdana, Arial, sans-serif;
	border: 1px solid #BDC7D8;
	padding: .5em;
	width: 100%;

	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
}

textarea {
	height: 40px;
}

#thewire-textarea {
	border: 0px;
	font-family: Verdana,Arial,Helvetica,sans-serif;
	min-height: 60px!important;
	padding: 4px!important;
	resize: vertical;
}

#thewire-characters-remaining {
	float: left!important;
	font-size: 11px;
	font-weight: normal!important;
	min-width: 17%;
	text-align: left!important;
}

#browser.elgg-foot.mts {
	font-size: 11px;
	/*padding: 10px 5px 9px 5px;*/
	padding: 5px;
}

.elgg-longtext-control {
	float: right;
	margin-left: 14px;
	font-size: 80%;
	cursor: pointer;
}


.elgg-input-access {
	margin:5px 0 0 0;
}

input[type="checkbox"],
input[type="radio"] {
	margin:0 3px 0 0;
	padding:0;
	border:none;
	width:auto;
}

.poll_post input[type="radio"] {
	margin: 2px 3px 2px 0;
	vertical-align: middle;
}

.elgg-input-checkboxes.elgg-horizontal li,
.elgg-input-radio.elgg-horizontal li {
	display: inline;
	padding-right: 10px;
}

.ui-datepicker {
	background:white;
	border-bottom: 2px solid #293E6C;
}	

.ui-datepicker-header {
	text-align: center;
	background: #6D84B7;
	color: white;
	font-weight: bold;
	padding: 3px 3px 4px;
	vertical-align:center;
	border: 1px solid #3A589B;
	border-width: 0 1px;
}

.ui-datepicker-next,
.ui-datepicker-prev {
	text-decoration: none;
	color:white;
	width: 14.2857%;
}

.ui-datepicker-next {
	padding-right: 3px;
	float:right;
}

.ui-datepicker-prev {
	padding-left: 3px;
	float:left;
}

.ui-datepicker-calendar {
	width: 100%;
	border-collapse: separate;
	border: 1px solid #777;
	border-width: 0 1px;
}

.ui-datepicker-calendar th {
	background: #F2F2F2;
	border-bottom: 1px solid #BBB;
	/*font-size: 9px;*/
	font-weight: bold;
	padding: 3px 2px;
	text-align: center;
}

.ui-datepicker-calendar td {
	padding: 0;
}

.ui-datepicker-calendar a {
	display: block;
	margin: 1px;
	padding: 4px;
	border: 1px solid white;
	color: #666;
	cursor: pointer;
	text-align:center;
	text-decoration: none;
}

.ui-datepicker-calendar .ui-datepicker-current-day > a {
	font-weight: bold;
	background: #DDD;
}

.ui-datepicker-calendar .ui-state-hover {
	color: #3B5998;
	border-color: #BEC8DD;
	background-color: #DFE4EE;
}


/* ***************************************
	FRIENDS PICKER
*************************************** */
.friends-picker-container h3 {
	font-size:4em !important;
	text-align: left;
	margin:10px 0 20px !important;
	color:#999 !important;
	background: none !important;
	padding:0 !important;
}
.friends-picker .friends-picker-container .panel ul {
	text-align: left;
	margin: 0;
	padding:0;
}
.friends-picker-wrapper {
	margin: 0;
	padding:0;
	position: relative;
	width: 608px;
}
.friends-picker {
	position: relative;
	overflow: hidden;
	margin: 0;
	padding:0;
	width: 608px;
	height: auto;
	background-color: #dedede;

	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	border-radius: 8px;
}
.friendspicker-savebuttons {
	background: white;

	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	border-radius: 8px;

	margin:0 10px 10px;
}
.friends-picker .friends-picker-container { /* long container used to house end-to-end panels. Width is calculated in JS  */
	position: relative;
	left: 0;
	top: 0;
	width: 608px;
	list-style-type: none;
}
.friends-picker .friends-picker-container .panel {
	float:left;
	height: 100%;
	position: relative;
	width: 608px;
	margin: 0;
	padding:0;
}
.friends-picker .friends-picker-container .panel .wrapper {
	margin: 0;
	padding:4px 10px 10px 10px;
	min-height: 430px;
}
.friends-picker-navigation {
	margin: 0 0 10px;
	padding:0 0 10px;
	border-bottom:1px solid #ccc;
}
.friends-picker-navigation ul {
	list-style: none;
	padding-left: 0;
}
.friends-picker-navigation ul li {
	float: left;
	margin:0;
	background:white;
}
.friends-picker-navigation a {
	font-weight: bold;
	text-align: center;
	background: white;
	color: #999;
	text-decoration: none;
	display: block;
	padding: 0;
	width:20px;

	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
}
.tabHasContent {
	background: white;
	color:#333 !important;
}
.friends-picker-navigation li a:hover {
	background: #333;
	color:white !important;
}
.friends-picker-navigation li a.current {
	background: #4690D6;
	color:white !important;
}
.friends-picker-navigation-l, .friends-picker-navigation-r {
	position: absolute;
	top: 46px;
	text-indent: -9000em;
}
.friends-picker-navigation-l a, .friends-picker-navigation-r a {
	display: block;
	height: 40px;
	width: 40px;
}
.friends-picker-navigation-l {
	right: 300px;
	z-index:1;
}
.friends-picker-navigation-r {
	right: 250px;
	z-index:1;
}
.friends-picker-navigation-l {
	background: url("<?php echo elgg_get_site_url(); ?>_graphics/friendspicker.png") no-repeat left top;
}
.friends-picker-navigation-r {
	background: url("<?php echo elgg_get_site_url(); ?>_graphics/friendspicker.png") no-repeat -60px top;
}
.friends-picker-navigation-l:hover {
	background: url("<?php echo elgg_get_site_url(); ?>_graphics/friendspicker.png") no-repeat left -44px;
}
.friends-picker-navigation-r:hover {
	background: url("<?php echo elgg_get_site_url(); ?>_graphics/friendspicker.png") no-repeat -60px -44px;
}
.friendspicker-savebuttons .elgg-button-submit,
.friendspicker-savebuttons .elgg-button-cancel {
	margin:5px 20px 5px 5px;
}
.friendspicker-members-table {
	background: #dedede;

	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	border-radius: 8px;

	margin:10px 0 0;
	padding:10px 10px 0;
}

/* ***************************************
	USER PICKER
*************************************** */

.user-picker .user-picker-entry {
	clear:both;
	height:25px;
	padding:5px;
	margin-top:5px;
	border-bottom:1px solid #cccccc;
}
.user-picker-entry .elgg-button-delete {
	margin-right:10px;
}
