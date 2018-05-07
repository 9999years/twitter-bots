<?php
require_once("TwitterAPIExchange.php");

function searchArgs($string) {
	global $argv;
	for($i = 0; $i < count($argv); $i++) {
		if($argv[$i] == $string) {
			return true;
		}
	}
	return false;
}

function loadkeys($string) {
	if(!file_exists($string)) {
		throw new Exception("keys file " . $string . " not found");
	}
	return json_decode(fread(fopen($string, "r"), filesize($string)));
}

function settingsfromkeys($keys) {
	return array(
		'oauth_access_token'        => $keys->access_token,
		'oauth_access_token_secret' => $keys->access_token_secret,
		'consumer_key'              => $keys->consumer_key,
		'consumer_secret'           => $keys->consumer_secret
	);
}

function twitterfromkeysfile($string) {
	return new TwitterAPIExchange(settingsfromkeys(loadkeys($string)));
}

function u($hex) { //other funcs rely on this in the includes. fuck you
	return json_decode("\"\\u" . $hex . "\"");
}

function pickRandom($input) {
	return $input[rand(0,sizeof($input)-1)];
}

/*
 *function randomHex($length) {
 *    $result = "";
 *    for($i = 0; $i < $length; $i++) {
 *        $result .= "1234567890abcdef"[rand(0,16)];
 *    }
 *    return $result;
 *}
 */


function randomCharacter() {
	return u(dechex(rand(0,0x2c00)));
}

function aString($input) {
	if($input[0] == "a" || $input[0] == "e" || $input[0] == "i" || $input[0] == "o" || $input[0] == "u") {
		return "an " . $input;
	} else {
		return "a " . $input;
	}
}

?>
