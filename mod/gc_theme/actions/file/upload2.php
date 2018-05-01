<?php
/**
 * Elgg file uploader/edit action
 *
 * @package ElggFile
 */

// Get variables
elgg_load_library('elgg:file_tools');
$title = htmlspecialchars(get_input('title', '', false), ENT_QUOTES, 'UTF-8');
$access_id = (int) get_input("access_id");
$container_guid = (int) get_input('container_guid', 0);
$add_to_river= get_input("add_to_river",false);

if ($container_guid == 0) {
	$container_guid = elgg_get_logged_in_user_guid();
	$access_id = ACCESS_LOGGED_IN;
}

elgg_make_sticky_form('file');

	//echo var_export($_FILES,true);
	//exit;
// check if upload attempted and failed
if (!empty($_FILES['upload']['name']) && $_FILES['upload']['error'] != 0) {
	$error = elgg_get_friendly_upload_error($_FILES['upload']['error']);

	register_error($error);
	forward(REFERER);
}

// check whether this is a new file or an edit
$new_file = true;

	// must have a file if a new file upload
	if (empty($_FILES['upload']['name'])) {
		$error = elgg_echo('file:nofile');
		register_error($error);
		forward(REFERER);
	}
	$filename=$_FILES['upload']['name'];
	if ($filename) {
		$extension_array = explode('.', $filename);
		$file_extension = end($extension_array);
		$allowed_extensions = file_tools_allowed_extensions();
		if(! in_array(strtolower($file_extension), $allowed_extensions)) {
		//if (! preg_match('/^.*\.(avi|doc|docx|dot|drf|gif|htm|html|ics|jpeg|jpg|kml|log|mov|msg|odt|pdf|png|ppsx|ppt|pptm|pptx|pub|txt|xls|xlsm|xlsx|xps)$/i', $filename)) {
			register_error(elgg_echo('gc_theme:file:not_allowed'));
			forward(REFERER);
		}
	}

	$file = new FilePluginFile();
	$file->subtype = "file";

	// if no title on new upload, grab filename
	if (empty($title)) {
		$title = htmlspecialchars($_FILES['upload']['name'], ENT_QUOTES, 'UTF-8');
	}


$file->title = $title;
$file->access_id = $access_id;
$file->container_guid = $container_guid;

// we have a file upload, so process it
if (isset($_FILES['upload']['name']) && !empty($_FILES['upload']['name'])) {

	$prefix = "file/";

	// if previous file, delete it
	if ($new_file == false) {
		$filename = $file->getFilenameOnFilestore();
		if (file_exists($filename)) {
			unlink($filename);
		}

		// use same filename on the disk - ensures thumbnails are overwritten
		$filestorename = $file->getFilename();
		$filestorename = elgg_substr($filestorename, elgg_strlen($prefix));
	} else {
		$filestorename = elgg_strtolower(time().$_FILES['upload']['name']);
	}

	$file->setFilename($prefix . $filestorename);
	$file->originalfilename = $_FILES['upload']['name'];
	$mime_type = $file->detectMimeType($_FILES['upload']['tmp_name'], $_FILES['upload']['type']);

	$file->setMimeType($mime_type);
	$file->simpletype = elgg_get_file_simple_type($mime_type);

	// Open the file to guarantee the directory exists
	$file->open("write");
	$file->close();
	move_uploaded_file($_FILES['upload']['tmp_name'], $file->getFilenameOnFilestore());

	$guid = $file->save();

	// if image, we need to create thumbnails (this should be moved into a function)
	if ($guid && $file->simpletype == "image") {
		$file->icontime = time();
		
		$thumbnail = get_resized_image_from_existing_file($file->getFilenameOnFilestore(), 60, 60, true);
		if ($thumbnail) {
			$thumb = new ElggFile();
			$thumb->setMimeType($_FILES['upload']['type']);

			$thumb->setFilename($prefix."thumb".$filestorename);
			$thumb->open("write");
			$thumb->write($thumbnail);
			$thumb->close();

			$file->thumbnail = $prefix."thumb".$filestorename;
			unset($thumbnail);
		}

		$thumbsmall = get_resized_image_from_existing_file($file->getFilenameOnFilestore(), 153, 153, true);
		if ($thumbsmall) {
			$thumb->setFilename($prefix."smallthumb".$filestorename);
			$thumb->open("write");
			$thumb->write($thumbsmall);
			$thumb->close();
			$file->smallthumb = $prefix."smallthumb".$filestorename;
			unset($thumbsmall);
		}

		$thumblarge = get_resized_image_from_existing_file($file->getFilenameOnFilestore(), 600, 600, false);
		if ($thumblarge) {
			$thumb->setFilename($prefix."largethumb".$filestorename);
			$thumb->open("write");
			$thumb->write($thumblarge);
			$thumb->close();
			$file->largethumb = $prefix."largethumb".$filestorename;
			unset($thumblarge);
		}
	} elseif ($file->icontime) {
		// if it is not an image, we do not need thumbnails
		unset($file->icontime);
		
		$thumb = new ElggFile();
		
		$thumb->setFilename($prefix . "thumb" . $filestorename);
		$thumb->delete();
		unset($file->thumbnail);
		
		$thumb->setFilename($prefix . "smallthumb" . $filestorename);
		$thumb->delete();
		unset($file->smallthumb);
		
		$thumb->setFilename($prefix . "largethumb" . $filestorename);
		$thumb->delete();
		unset($file->largethumb);
	}
} else {
	// not saving a file but still need to save the entity to push attributes to database
	$file->save();
}

// file saved so clear sticky form
elgg_clear_sticky_form('file');


// handle results differently for new files and file updates
if ($guid) {
	$result	= array("uploaded" => 1, "fileName" => $filename, "url" => "file/view/".$file->guid."/$filename");
} else {
		// failed to save file object - nothing we can do about this
	$error = elgg_echo("file:uploadfailed");
	$result	= array("uploaded" => 0, "error" => $error);
}
        header("Content-Type: application/json");
        echo json_encode($result);
        exit();
