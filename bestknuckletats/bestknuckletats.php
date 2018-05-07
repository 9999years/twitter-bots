<?php

require_once("../common.php");

//knuckle tats bot 2kwhatever
$startTime = time();
mt_srand($startTime);

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


$twitter = twitterfromkeysfile("bestknuckletats.json");
$twitter_output = $twitter->buildOauth("https://api.twitter.com/1.1/statuses/update.json", "POST")
	->setPostfields(array(
		"status" => $status
	))
	->performRequest();
?>
