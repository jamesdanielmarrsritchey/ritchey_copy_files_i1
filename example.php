<?php
$location = realpath(dirname(__FILE__));
require_once $location . '/ritchey_copy_files_i1_v1.php';
$return = ritchey_copy_files_i1_v1("{$location}/temporary/source", "{$location}/temporary/destination", 'sha256', TRUE, TRUE);
if (is_array($return) === TRUE){
	print_r($return) . PHP_EOL;
} else if ($return === TRUE) {
	echo "TRUE" . PHP_EOL;
} else {
	echo "FALSE" . PHP_EOL;
}
?>