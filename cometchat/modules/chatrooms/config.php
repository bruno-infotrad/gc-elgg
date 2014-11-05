<?php

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* SETTINGS START */

$chatroomTimeout = '1800';
$lastMessages = '10';
$allowUsers = '1';
$armyTime = '1';
$displayFullName = '1';
$hideEnterExit = '0';
$minHeartbeat = '3000';
$maxHeartbeat = '12000';
$autoLogin = '0';
$messageBeep = '1';
$token = '5093280a2665575a62999e068b1e2db210596d41';


/* SETTINGS END */

if (USE_COMET == 1 && COMET_CHATROOMS == 1) {
	$minHeartbeat = $maxHeartbeat = REFRESH_BUDDYLIST.'000';
	$hideEnterExit = 1;
}

/* ADDITIONAL SETTINGS */

$chatroomLongNameLength = 60;	// The chatroom length after which characters will be truncated
$chatroomShortNameLength = 30;	// The chatroom length after which characters will be truncated

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
