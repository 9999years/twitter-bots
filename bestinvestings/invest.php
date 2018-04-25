<?php
//worst practice style guide

function u($hex) { //other funcs rely on this in the includes. fuck you
	return json_decode("\"\\u" . $hex . "\"");
}

require_once("industries.php"); //includes $industries, a list of ~280 industries
require_once("stocks_all.php"); //includes $tickers, symbol=>company. alt is tickers.php
require_once("adverbs.php"); //includes $adverbs, a list of adverbs
require_once("adjectives.php"); //...you get the deal
require_once("investvars.php");

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

function generatePunctuation($amount) {
	$punctuation = array(
		 "!"
		,"?"
		,","
		,"."
		);
	$result = "";
	for($i = 0; $i < $amount; $i++) {
		$result .= rand(0,3) ? (rand(0,2) ? "!" : (rand(0,2) ? "?,."[rand(0,2)] : "/\\-=+~!@#%^&*()"[rand(0,14)])) : randomCharacter();
		if(rand(0,10)*$i>5) {
			break;
		}
	}
	return $result;
}

function numsAreZero($input) {
	for($i = 0; $i < mb_strlen($input); $i++) {
		if(ord($input[$i]) > 0x30 && ord($input[$i]) < 0x3a) {
			return false;
		}
	}
	return true;
}

function corrupt($input, $amount) {
	for($i = 0; $i < $amount; $i++) {
		$index = rand(0, mb_strlen($input));
		$input = mb_substr($input, 0, $index) . randomCharacter() . mb_substr($input, $index);
	}
	return $input;
}


/*
 *function randomletter($n) {
 *    $str = "";
 *    for($i = 0; $i < $n; $i++) {
 *        $str .= chr(rand(64, 90));
 *    }
 *    return $str;
 *}
 */

function divinate_ticker() {
	global $actions, $markets, $prediction_verbs, $predictions, $adjectives, $adverbs, $industries, $tickers;

	$change = "0";

	while(numsAreZero($change)) {
		$symbol = array_rand($tickers);
		//$symbol_data = json_decode(file_get_contents("https://finance.yahoo.com/webservice/v1/symbols/" . $symbol . "/quote?format=json"));
		$change = substr(file_get_contents("http://download.finance.yahoo.com/d/quotes.csv?s=" . $symbol . "&f=p2"),1,-3);
		if(!is_string($change)) { continue; }
		if(!empty($change)) {
			$change_char = $change[0];
			$change_sign = $change[0] == "+" ? 1 : 0;
		} else {
			$change_char = "+";
			$change_sign = 1;
		}
	}
	return "$" . $symbol . " (" . $tickers[$symbol] . ") is " . ($change_sign ? "up" : "down") . " by " . $change . "%; " . pickRandom($actions) . " " . pickRandom($adverbs) . ", I " . pickRandom($prediction_verbs) . " " . aString(pickRandom($adjectives)) . " " . pickRandom($predictions) . generatePunctuation(5);
}

function suggest() {
	global $actions, $markets, $prediction_verbs, $predictions, $adjectives, $adverbs, $industries, $tickers;

	return pickRandom($adverbs) . " " . pickRandom($actions) . " " . pickRandom($industries) . " " . pickRandom($markets) . generatePunctuation(3);
}

function warn() {
	global $actions, $markets, $prediction_verbs, $predictions, $adjectives, $adverbs, $industries, $tickers;

	$symbol = array_rand($tickers);
	if(rand(0,1)) {
		return pickRandom($actions) . " $" . $symbol . " (" . $tickers[$symbol] . ") " . pickRandom($adverbs) . " " . u("2026") . "or else" . generatePunctuation(2);
	} else {
		return "If you don" . u("2019") . "t " . pickRandom($actions) . " $" . $symbol . " (" . $tickers[$symbol] . ")" . u("2026") . " well, let" . u("2019") . "s just say i told you so" . generatePunctuation(2);
	}
}

function news() {
	global $actions, $markets, $prediction_verbs, $predictions, $adjectives, $adverbs, $industries, $tickers;

	$news = simplexml_load_string(file_get_contents("https://finance.yahoo.com/rss/topfinstories"));
	$news_story = $news->channel->item[rand(0,9)];
	return $news_story->title . ", you know what to do" . u("2014") . "" . pickRandom($actions) . generatePunctuation(2);
}

function generateStatus() {
	$status = "";
	switch(rand(0,3)) {
		case 0: $status = divinate_ticker(); break;
		case 1: $status = suggest(); break;
		case 2: $status = warn(); break;
		case 3: $status = news(); break;
	}
	return corrupt(ucfirst($status),rand(0,5));
}

require_once("TwitterAPIExchange.php");

$logfile = fopen("invest.log","a");
fwrite($logfile,"\n" . date("d, F Y, H:i:s") . ": " . __FILE__ . " run,");

if(rand(0,1)) {
	fwrite($logfile," status generated.");
	$keys = json_decode(fread(fopen("investkeys","r"),filesize("investkeys")));
	$settings = array(
		'oauth_access_token' => $keys->access_token,
		'oauth_access_token_secret' => $keys->access_token_secret,
		'consumer_key' => $keys->consumer_key,
		'consumer_secret' => $keys->consumer_secret
	);
	$twitter = new TwitterAPIExchange($settings);
	fwrite($logfile, " Output: " . $twitter->buildOauth("https://api.twitter.com/1.1/statuses/update.json", "POST")
		->setPostfields(array(
			"status" => mb_substr(generateStatus(),0,140)
		))
		->performRequest());
} else {
	fwrite($logfile," status not generated.");
}
?>
