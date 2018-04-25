<?
//$prefixes, $suffixes, $infixes
require_once("./roots.php");

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

function generateWord() {
	global $infixes, $prefixes, $suffixes;
	$word = '';
	if(mt_rand(0, 2)) {
		$word .= pickRandom($prefixes);
	}
	$infix_count = mt_rand(1, 3);
	for($i = 0; $i < $infix_count; $i++) {
		$word .= pickRandom($infixes);
	}
	if(mt_rand(0, 2)) {
		$word .= pickRandom($suffixes);
	}
	return $word;
}


if(searchArgs("test")) {
	for($i = 0; $i < 100; $i++) {
		$word = generateWord();
		echo "    " . $word . str_repeat(" ", 30 - strlen($word));
		if($i % 2) {
			echo "\n";
		}
	}
	die();
}

require_once("../TwitterAPIExchange.php");

$keys = json_decode(fread(fopen("fakewordskeys","r"),filesize("fakewordskeys")));

$settings = array(
	'oauth_access_token'        => $keys->access_token,
	'oauth_access_token_secret' => $keys->access_token_secret,
	'consumer_key'              => $keys->consumer_key,
	'consumer_secret'           => $keys->consumer_secret
);

$twitter = new TwitterAPIExchange($settings);
$twitter_output = $twitter->buildOauth("https://api.twitter.com/1.1/statuses/update.json", "POST")
	->setPostfields(array(
		"status" => generateWord()
	))
	->performRequest();

if(searchArgs("storm")) {
	for($i = 0; $i < 100; $i++) {
		$twitter_output = $twitter->buildOauth("https://api.twitter.com/1.1/statuses/update.json", "POST")
			->setPostfields(array(
				"status" => generateWord()
			))
		->performRequest();
	}
	die();
}
?>
