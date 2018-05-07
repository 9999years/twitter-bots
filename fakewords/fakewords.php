<?
require_once("../common.php");
//$prefixes, $suffixes, $infixes
require_once("./roots.php");

$startTime = time();
mt_srand($startTime);

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


$twitter = twitterfromkeysfile("fakewords.json");
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
