<?php
include_once("common.php");

$logfile = fopen("garden.log","a");
fwrite($logfile,"\n" . date("d F Y, H:i:s") . ": " . __FILE__ . " run,");

function random($min, $max) {
	if($min > $max) {
		$tmp = $min;
		$min = $max;
		$max = $tmp;
	}
	return mt_rand($min, $max);
}

$planets = array(
	 "\u{1f311}" // New Moon Symbol
	,"\u{1f312}" // Waxing Crescent Moon Symbol
	,"\u{1f313}" // First Quarter Moon Symbol
	,"\u{1f314}" // Waxing Gibbous Moon Symbol
	,"\u{1f315}" // Full Moon Symbol
	,"\u{1f316}" // Waning Gibbous Moon Symbol
	,"\u{1f317}" // Last Quarter Moon Symbol
	,"\u{1f318}" // Waning Crescent Moon Symbol
	,"\u{1f319}" // Crescent Moon
	,"\u{1f31a}" // New Moon With Face
	,"\u{1f31b}" // First Quarter Moon With Face
	,"\u{1f31c}" // Last Quarter Moon With Face
	,"\u{2600}" // Black Sun With Rays
	,"\u{1f31d}" // Full Moon With Face
	,"\u{1f31e}" // Sun With Face
	);

$weathers = array(
	 "\u{2b50}" // White Medium Star
	,"\u{1f31f}" // Glowing Star
	,"\u{1f320}" // Shooting Star
	,"\u{2601}" // Cloud
	,"\u{26c5}" // Sun Behind Cloud
	,"\u{26c8}" // Thunder Cloud and Rain
	,"\u{1f324}" // White Sun With Small Cloud
	,"\u{1f325}" // White Sun Behind Cloud
	,"\u{1f326}" // White Sun Behind Cloud With Rain
	,"\u{1f327}" // Cloud With Rain
	,"\u{1f328}" // Cloud With Snow
	,"\u{1f329}" // Cloud With Lightning
	,"\u{1f32a}" // Cloud With Tornado
	,"\u{1f32b}" // Fog
	,"\u{26a1}" // High Voltage Sign
	,"\u{2744}" // Snowflake
	,"\u{2604}" // Comet
	,"\u{1f4a7}" // Droplet
	,"\u{1f4a6}" // Splashing Sweat Symbol
	);

$animals = array(
	 "\u{1f435}"
	,"\u{1f412}"
	,"\u{1f436}"
	,"\u{1f415}"
	,"\u{1f429}"
	,"\u{1f43a}"
	,"\u{1f431}"
	,"\u{1f408}"
	,"\u{1f42f}"
	,"\u{1f405}"
	,"\u{1f406}"
	,"\u{1f434}"
	,"\u{1f40e}"
	,"\u{1f42e}"
	,"\u{1f402}"
	,"\u{1f403}"
	,"\u{1f404}"
	,"\u{1f437}"
	,"\u{1f416}"
	,"\u{1f417}"
	,"\u{1f43d}"
	,"\u{1f40f}"
	,"\u{1f411}"
	,"\u{1f410}"
	,"\u{1f42a}"
	,"\u{1f42b}"
	,"\u{1f418}"
	,"\u{1f42d}"
	,"\u{1f401}"
	,"\u{1f400}"
	,"\u{1f439}"
	,"\u{1f430}"
	,"\u{1f407}"
	,"\u{1f43f}"
	,"\u{1f43b}"
	,"\u{1f428}"
	,"\u{1f43c}"
	,"\u{1f414}"
	,"\u{1f413}"
	,"\u{1f423}"
	,"\u{1f424}"
	,"\u{1f425}"
	,"\u{1f426}"
	,"\u{1f427}"
	,"\u{1f54a}"
	,"\u{1f438}"
	,"\u{1f40a}"
	,"\u{1f422}"
	,"\u{1f40d}"
	,"\u{1f432}"
	,"\u{1f409}"
	,"\u{1f433}"
	,"\u{1f40b}"
	,"\u{1f42c}"
	,"\u{1f41f}"
	,"\u{1f420}"
	,"\u{1f421}"
	,"\u{1f419}"
	,"\u{1f41a}"
	,"\u{1f40c}"
	,"\u{1f41b}"
	,"\u{1f41c}"
	,"\u{1f41d}"
	,"\u{1f41e}"
	,"\u{1f577}"
	//Unicode 9.0 ones start here!
	,"\u{1f98d}" // Gorilla
	,"\u{1f98a}" // Fox Face
	,"\u{1f98c}" // Deer
	,"\u{1f98f}" // Rhinoceros
	,"\u{1f987}" // Bat
	,"\u{1f985}" // Eagle
	,"\u{1f986}" // Duck
	,"\u{1f989}" // Owl
	,"\u{1f98e}" // Lizard
	,"\u{1f988}" // Shark
	,"\u{1f990}" // Shrimp
	,"\u{1f991}" // Squid
	,"\u{1f98b}" // Butterfly
	);

$plants = array(
	 "\u{1f490}"
	,"\u{1f338}"
	,"\u{1f3f5}"
	,"\u{1f339}"
	,"\u{1f33a}"
	,"\u{1f33b}"
	,"\u{1f33c}"
	,"\u{1f337}"
	,"\u{1f331}"
	,"\u{1f332}"
	,"\u{1f333}"
	,"\u{1f334}"
	,"\u{1f335}"
	,"\u{1f33e}"
	,"\u{1f33f}"
	,"\u{2618}"
	,"\u{1f340}"
	,"\u{1f341}"
	,"\u{1f342}"
	,"\u{1f343}"
	,"\u{1f347}"
	,"\u{1f348}"
	,"\u{1f349}"
	,"\u{1f34a}"
	,"\u{1f34b}"
	,"\u{1f34c}"
	,"\u{1f34d}"
	,"\u{1f34e}"
	,"\u{1f34f}"
	,"\u{1f350}"
	,"\u{1f351}"
	,"\u{1f352}"
	,"\u{1f353}"
	,"\u{1f345}"
	,"\u{1f346}"
	,"\u{1f33d}"
	,"\u{1f336}"
	,"\u{1f344}"
	,"\u{1f330}"
	,"\u{1f38d}"
	,"\u{2618}"
	//Unicode 9.0 ones start here!
	,"\u{1f940}" // Wilted Flower
	,"\u{1f95d}" // Kiwifruit
	,"\u{1f951}" // Avocado
	,"\u{1f954}" // Potato
	,"\u{1f955}" // Carrot
	,"\u{1f952}" // Cucumber
	);

function pickRandom($input) {
	return $input[random(0,sizeof($input)-1)];
}

/*
 *function randomCharacter() {
 *    return u(dechex(random(0,0x2c00)));
 *}
 */

function randomCharacter($string) {
	return mb_substr($string, random(0, mb_strlen($string)), 1);
}

function fillArray($obj, $iMax, $jMax, $array) {
	for($i = 0; $i < $iMax; $i++) {
		for($j = 0; $j < $jMax; $j++) {
			$array[$i][$j] = $obj;
		}
	}
	return $array;
}

function generateStatus() {
	global $plants, $animals, $weathers, $planets;

	$sideLength = random(2,10);
	$weatherHeight = random(0,2);
	$varianceAmount = random(10,20);
	$garden = fillArray(pickRandom($plants), $sideLength, $sideLength, array());
	if($weatherHeight) {
		$garden = fillArray(pickRandom($weathers), $weatherHeight, $sideLength, $garden);
		for($i = 0; $i < $varianceAmount/4; $i++) {
			$garden[random(0,$weatherHeight-1)][random(0,$sideLength-1)] =
			pickRandom($weathers);
		}
		$garden[random(0,$weatherHeight-1)][random(0,$sideLength-1)] =
		pickRandom($planets);
	}
	for($i = 0; $i < $varianceAmount; $i++) {
		$garden[random($weatherHeight,$sideLength-1)][random(0,$sideLength-1)] =
		random(0,1) ? pickRandom($plants) : pickRandom($animals);
	}
	$result = "";
	for($i = 0; $i < $sideLength; $i++) {
		$result .= implode("", $garden[$i]) . "\n";
	}
	return $result;
}

$status = mb_substr(generateStatus(),0,140);

if(searchArgs("silent")) {
	fwrite($logfile, "Output SILENCED.");
	echo $status . "\n\n";
	die();
}

require_once("TwitterAPIExchange.php");

$keys = json_decode(fread(fopen("gardenkeys","r"),filesize("gardenkeys")));

$settings = array(
	'oauth_access_token' => $keys->access_token,
	'oauth_access_token_secret' => $keys->access_token_secret,
	'consumer_key' => $keys->consumer_key,
	'consumer_secret' => $keys->consumer_secret
);
$twitter = new TwitterAPIExchange($settings);
fwrite($logfile, "Output: " . $twitter->buildOauth("https://api.twitter.com/1.1/statuses/update.json", "POST")
	->setPostfields(array(
		"status" => $status
	))
	->performRequest());
fwrite($logfile, "\n");

?>
