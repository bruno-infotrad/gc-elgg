<?php
/**
 * Get the temporary file name of an uploaded file.
 * (Returns false if there was an issue.)
 *
 * @param string $input_name The name of the file input field on the submission form
 *
 * @return mixed|false The temporary file name of the file, or false on failure.
 */
function get_uploaded_filename($input_name) {
	// If the file exists ...
	if (isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] == 0) {
		return $_FILES[$input_name]['tmp_name'];
	}
	return false;
}

/**
 * 
 * @param unknown_type $length
 * @return string
 */
function generateRandomString($length = 10) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, strlen($characters) - 1)];
	}
	return $randomString;
}
