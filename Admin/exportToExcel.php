<?php

session_start();

$filename = time() . ".xls";

header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-Type: application/vnd.ms-excel");


array_walk($_SESSION['export'], 'cleanData');
print_r(implode("\r",str_replace("|","\t",$_SESSION['export'])));


function cleanData(&$str) {

	$str = strtoupper(($str));

	// escape tab characters

	$str = preg_replace("/\t/", "\\t", $str);

	// escape new lines

	$str = preg_replace("/\r?\n/", "\\n", $str);

	// convert 't' and 'f' to boolean values

	if($str == 't') 

		$str = 'TRUE'; 

	if($str == 'f') 

		$str = 'FALSE'; 



	// force certain number/date formats to be imported as strings

	if(preg_match("/^0/", $str) || preg_match("/^\+?\d{8,}$/", $str) || preg_match("/^\d{4}.\d{1,2}.\d{1,2}/", $str)) {

		$str = "'$str"; 

	}

	// escape fields that include double quotes 

	if(strstr($str, '"')) 

		$str = '"' . str_replace('"', '""', $str) . '"';

} 

?>