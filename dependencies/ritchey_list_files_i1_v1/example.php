<?php
$location = realpath(dirname(__FILE__));
require_once $location . '/ritchey_list_files_i1_v1.php';
$return = ritchey_list_files_i1_v1("{$location}", TRUE);
if ($return == TRUE){
	print_r($return);
} else {
	echo "FALSE\n";
}
?>