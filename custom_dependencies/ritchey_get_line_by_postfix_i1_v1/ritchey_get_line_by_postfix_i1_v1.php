<?php
#Name:Ritchey Get Line By Postfix i1 v1
#Description:Get the content of a line in a file based on the start of the line. Returns first matching line on success. Returns "FALSE" on failure.
#Notes:Optional arguments can be "NULL" to skip them in which case they will use default values. Line endings are ignored when comparing the postfix.
#Arguments:'source' (required) is the file to read from. 'postfix' (required) is the postfix to search for. 'display_errors' (optional) indicates if errors should be displayed.
#Arguments (Script Friendly):source:file:required,prefix:string:required,display_errors:bool:optional
#Content:
if (function_exists('ritchey_get_line_by_postfix_i1_v1') === FALSE){
function ritchey_get_line_by_postfix_i1_v1($source, $postfix, $display_errors = NULL){
	$errors = array();
	if (@is_file($source) === FALSE){
		$errors[] = "source";
	}
	if (@isset($postfix) === TRUE){
		if ($postfix === ''){
			$errors[] = "prefix";
		}
	} else {
		$errors[] = "prefix";
	}
	if ($display_errors === NULL){
		$display_errors = FALSE;
	} else if ($display_errors === TRUE){
		#Do Nothing
	} else if ($display_errors === FALSE){
		#Do Nothing
	} else {
		$errors[] = "display_errors";
	}
	##Task []
	if (@empty($errors) === TRUE){
		$handle = @fopen($source, 'r');
		$line = '';
		$postfix_length = @strlen($postfix);
		$postfix_offset = -$postfix_length;
		while (@feof($handle) !== TRUE AND @substr(rtrim($line), $postfix_offset, $postfix_length) != $postfix) {
			$line = @fgets($handle);
		}
		@fclose($handle);
		###Determine if a match was found, or not.
		if (@substr(rtrim($line), $postfix_offset, $postfix_length) != $postfix){
			$errors[] = "line - no line found with postfix";
			goto result;
		}
	}
	result:
	##Display Errors
	if ($display_errors === TRUE){
		if (@empty($errors) === FALSE){
			$message = @implode(", ", $errors);
			if (function_exists('ritchey_get_line_by_postfix_i1_v1_format_error') === FALSE){
				function ritchey_get_line_by_postfix_i1_v1_format_error($errno, $errstr){
					echo $errstr;
				}
			}
			set_error_handler("ritchey_get_line_by_postfix_i1_v1_format_error");
			trigger_error($message, E_USER_ERROR);
		}
	}
	##Return
	if (@empty($errors) === TRUE){
		return $line;
	} else {
		return FALSE;
	}
}
}
?>