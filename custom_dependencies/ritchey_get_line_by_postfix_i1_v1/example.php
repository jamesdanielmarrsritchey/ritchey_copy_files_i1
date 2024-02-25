<?php
$location = realpath(dirname(__FILE__));
require_once $location . '/ritchey_get_line_by_postfix_i1_v1.php';
$return = ritchey_get_line_by_postfix_i1_v1("{$location}/ritchey_get_line_by_postfix_i1_v1.php", 'display_errors:bool:optional', TRUE);
if ($return == TRUE){
	print_r($return);
	echo "\n";
} else {
	echo "FALSE";
}
?>