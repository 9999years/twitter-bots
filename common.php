<?php
function searchArgs($string) {
	global $argv;
	for($i = 0; $i < count($argv); $i++) {
		if($argv[$i] == $string) {
			return true;
		}
	}
	return false;
}
?>
