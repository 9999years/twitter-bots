<?php
//knuckle tats bot 2kwhatever
$startTime = time();
mt_srand($startTime);

function searchArgs($string) {
	global $argv;
	for($i = 0; $i < count($argv); $i++) {
		if($argv[$i] == $string) {
			return true;
		}
	}
	return false;
}

function pickRandom($input) {
	return $input[rand(0,sizeof($input)-1)];
}

include("four_letter_words.php");
include("eight_letter_words.php");
//includes $fourletterwords and $eight...

function generateTweet() {
	global $fourletterwords, $eightletterwords;
	if(mt_rand(0, 4)) {
		return strtoupper(pickRandom($fourletterwords) . " " . pickRandom($fourletterwords));
	} else {
		$word = pickRandom($eightletterwords);
		return strtoupper(mb_substr($word, 0, 4) . " " . mb_substr($word, 4));
	}
}

if(searchArgs("test")) {
	for($i = 0; $i < 100; $i++) {
		echo "                              " . generateTweet() . "\n";
	}
	die();
}

$status = generateTweet();

require_once("TwitterAPIExchange.php");

$keys = json_decode(fread(fopen("knucklekeys","r"),filesize("knucklekeys")));

$settings = array(
	'oauth_access_token'        => $keys->access_token,
	'oauth_access_token_secret' => $keys->access_token_secret,
	'consumer_key'              => $keys->consumer_key,
	'consumer_secret'           => $keys->consumer_secret
);

$twitter = new TwitterAPIExchange($settings);
$twitter_output = $twitter->buildOauth("https://api.twitter.com/1.1/statuses/update.json", "POST")
	->setPostfields(array(
		"status" => $status
	))
	->performRequest();
?>
