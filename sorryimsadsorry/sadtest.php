<?php

function randomCharacter($string) {
	return $string[rand(0, mb_strlen($string))];
}

function gen() {
	$cries = "’‘”“";
	return randomCharacter($cries);
}

echo gen();

?>
