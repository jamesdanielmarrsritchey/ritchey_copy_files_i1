<?php
#Name:Ritchey Copy Files i1 v1
#Description:Copy all files in a directory (recursively), to another directory. Returns "TRUE" if nothing needed to be copied. Returns an array if any files needed to be copied. Returns "FALSE" on failure.
#Notes:Optional arguments can be "NULL" to skip them in which case they will use default values. If a file exists the source file checksum will be compared to a checksum listed in a checksums file in the destination. If there is not such file, then it will be compared to the checksum of the destination file. Files that exist in the destination, but not in the source, are not evaluated, or removed. This function copies files, and creates directories as needed. However, it does not copy empty folders.
#Arguments:'source' (required) is the folder containing the files to copy. 'destination' (required) is the folder to copy files to. 'hashing_algorithm' (optional) is the hashing algorithm to use when comparing files. Valid values are 'sha256'. Default value is 'sha256'. 'display_errors' (optional) indicates if errors should be displayed. 'display_progress_cli' (optional) indicates if any progress related outputs should be made. They are formatted with the commandline in mind.
#Arguments (Script Friendly):source:path:required,destination:path:required,hashing_algorithm:string:optional,display_errors:bool:optional,display_progress_cli:bool:optional
#Content:
#<value>
if (function_exists('ritchey_copy_files_i1_v1') === FALSE){
function ritchey_copy_files_i1_v1($source, $destination, $hashing_algorithm = NULL, $display_errors = NULL, $display_progress_cli = NULL){
	$errors = array();
	$location = realpath(dirname(__FILE__));
	if (@is_dir($source) === FALSE){
		$errors[] = 'source';
	}
	if (@is_dir($destination) === FALSE){
		$errors[] = 'destination';
	}
	if ($hashing_algorithm === NULL){
		$hashing_algorithm = 'sha256';
	} else if ($hashing_algorithm === 'sha256'){
		//Do nothing
	} else {
		$errors[] = "hashing_algorithm";
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
	if ($display_progress_cli === NULL){
		$display_progress_cli = FALSE;
	} else if ($display_progress_cli === TRUE){
		#Do Nothing
	} else if ($display_progress_cli === FALSE){
		#Do Nothing
	} else {
		$errors[] = "display_progress_cli";
	}
	##Task
	if (@empty($errors) === TRUE){
		###Get a list of all files in source
		$location = realpath(dirname(__FILE__));
		require_once $location . '/dependencies/ritchey_list_files_i1_v1/ritchey_list_files_i1_v1.php';
		$files = ritchey_list_files_i1_v1($source, FALSE);
		###For each file in source copy to destination
		$result = array();
		if ($display_progress_cli === TRUE){
			$n1 = count($files);
			$n2 = 0;	
		}
		foreach ($files as &$item1){
			if ($display_progress_cli === TRUE){
				$n2++;
				echo "Checking: {$n2}/{$n1} ('$item1')." . PHP_EOL;
			}
			//Check if file exists in destination
			$item1_destination = $destination . substr($item1, strlen($source), null);
			if (is_file($item1_destination) === TRUE){
				if ($display_progress_cli === TRUE){
					echo "Status: {$n2}/{$n1} exists in destination." . PHP_EOL;
				}
				//Hash the file
				$source_checksum = FALSE;
				if ($hashing_algorithm === 'sha256'){
					$source_checksum = hash_file('sha256', $item1);
				}
				//Check if there is a checksums file, and if it contains a checksum for the current file.
				$checksums_file = FALSE;
				$checksums_file_checksum = FALSE;
				if ($hashing_algorithm === 'sha256'){
					$checksums_file = dirname($item1_destination) . '/sha256.txt';
					//Check if the checksums file contains the file in question
					if (is_file($checksums_file) === TRUE){
						$location = realpath(dirname(__FILE__));
						require_once $location . '/custom_dependencies/ritchey_get_line_by_postfix_i1_v1/ritchey_get_line_by_postfix_i1_v1.php';
						$checksums_file_checksum = ritchey_get_line_by_postfix_i1_v1($checksums_file, basename($item1_destination), FALSE);
						if ($checksums_file_checksum !== FALSE){
							$checksums_file_checksum = explode(' ', $checksums_file_checksum);
							$checksums_file_checksum = $checksums_file_checksum[0];
						}
					}
				}
				//Hash the destination file if needed.
				$destination_checksum = FALSE;
				if ($checksums_file_checksum !== FALSE){
					$destination_checksum = $checksums_file_checksum;
				} else {
					$destination_checksum = hash_file('sha256', $item1_destination);
				}
				//Copy the file if needed.
				if ($source_checksum !== $destination_checksum){
					if ($display_progress_cli === TRUE){
						echo "Status: {$n2}/{$n1} source checksum does not match destination checksum." . PHP_EOL;
					}
					$item1_destination_folder = dirname($item1_destination);
					if (is_dir($item1_destination_folder) === FALSE){
						mkdir($item1_destination_folder, 0777, true);
					}
					copy($item1, $item1_destination);
					$result[] = $item1 . ',overwritten';
				} else {
					if ($display_progress_cli === TRUE){
						echo "Status: {$n2}/{$n1} source checksum matches destination checksum." . PHP_EOL;
					}
				}
			} else {
				//Copy the file
				if ($display_progress_cli === TRUE){
					echo "Status: {$n2}/{$n1} does not exist in destination" . PHP_EOL;
				}
				$item1_destination_folder = dirname($item1_destination);
				if (is_dir($item1_destination_folder) === FALSE){
					mkdir($item1_destination_folder, 0777, true);
				}
				copy($item1, $item1_destination);
				$result[] = $item1 . ',written';
			}
		}
		unset($item1);
	}
	result:
	##Display Errors
	if ($display_errors === TRUE){
		if (@empty($errors) === FALSE){
			$message = @implode(", ", $errors);
			if (function_exists('ritchey_copy_files_i1_v1_format_error') === FALSE){
				function ritchey_copy_files_i1_v1_format_error($errno, $errstr){
					echo $errstr;
				}
			}
			set_error_handler("ritchey_copy_files_i1_v1_format_error");
			trigger_error($message, E_USER_ERROR);
		}
	}
	##Return
	if (@empty($errors) === TRUE){
		if (@empty($result) === TRUE){
			return TRUE;
		} else {
			return $result;
		}
	} else {
		return FALSE;
	}
}
}
#</value>
?>