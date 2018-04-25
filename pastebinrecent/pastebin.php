<?php
include_once("common.php");

$startTime = time();
mt_srand($startTime);

$logfile = fopen("pastebin.log","a");
fwrite($logfile,"\n" . date("d F Y, H:i:s") . " (" . $startTime . "): " . __FILE__ . " run.\n");

function generateStatus($logfile) {
	//eugh
	//trying to keep spam, porn, dox, viruses, and racism low here
	$blacklist = Array(
		//slurs
		 "nigger"
		,"nigga"
		,"cunt"
		,"bitch"
		,"fag"
		,"tranny"
		,"slut"
		,"beaner"
		,"spic"
		,"gypsy"
		,"rape"
		,"chink"
		,"gook"
		,"coon"
		,"nazi"
		,"kkk"
		,"jew"
		,"klan"
		,"thug"
		,"muslim"
		//porn
		,"************************"
		,"sex"
		,"milf"
		,"video"
		,"nude"
		,"private show"
		,"xxx"
		,"torrent"
		,"blowjob"
		,".mkv"
		,".mov"
		,".mp4"
		,".avi"
		,"daddy"
		,"brazzer"
		,"covergirlx"
		,"artofx"
		,"cum"
		,"tits"
		,"epicomg.com"
		,"porn"
		//download sites
		//nothing against them just potentially unsafe
		//or spammy
		,"rapidgator.net"
		,"uploaded.net"
		,"nitroflare.net"
		,"fichier.com"
		,"mediafire.com"
		,"mega.nz"
		,"mega.co.nz"
		,"blogspot.com"
		,"4shared.com"
		,"siterip.club"
		,"adf.ly"
		,"free"
		,"download"
		,"rar"
		,"x264"
		,"1080p"
		,"720p"
		,"livestream"
		//pii
		,"ccn"
		,"ccv"
		,"ssn"
		,"address"
		,"adress"
		,"po box"
		,"dox"
		,"ip:"
		,"name:"
		,"website:"
		,"twitter"
		,"password"
		,"skype"
		,"@gmail.com"
		,"leet haxor"
		);
	ini_set("display_errors", 1);
	$rawjson = false;
	while($rawjson === false) {
		$rawjson = @file_get_contents("https://scrape.pastebin.com/api_scraping.php?limit=50");
		sleep(1);
	}
	//grab array of random offset
	$json = array_slice(json_decode($rawjson), mt_rand(0,80));
	$txt = false;
	//error_reporting(E_ERROR | E_PARSE);
	foreach($json as $paste) {
		$url = "http://pastebin.com/raw/" . $paste->key;
		fwrite($logfile, "Checking " . $url . "\n");
		$txt = false;
		for($i = 0; $txt === false && $i <= 5; $i++) {
			$txt = @file_get_contents($paste->scrape_url);
			sleep(1);
		}
		if($txt === false) {
			// nothing loaded
			continue;
		}
		//$txt = @file_get_contents($url, FALSE, NULL, 0, 4096);
		$blacklist_found = FALSE;
		foreach($blacklist as $word) {
			if(mb_strpos(mb_strtolower($txt), $word) !== FALSE) {
				fwrite($logfile, "Failed! Blacklisted word found: " . $word . "\nStatus:\n" . $txt);
				//blacklisted word found
				$blacklist_found = TRUE;
				break;
			}
		}
		if($blacklist_found === FALSE) {
			//nothing in the blacklist!
			break;
		}
		//let the api rest if we need another file
		sleep(1);
	}
	//read 200 chars from file
	$length = mb_strlen($txt);
	if($length > 140) {
		$txt = mb_substr($txt, mt_rand(0, mb_strlen($txt) - 140), 140);
	}
	fwrite($logfile, "FINAL STATUS:\n" . $txt . "\n");
	//grab middle 140
	return $txt;
}

if(searchArgs("test")) {
	fwrite($logfile, "Generating 100 test statuses:");
	for($i = 0; $i < 100; $i++) {
		$status = mb_substr(generateStatus($logfile),0,140);
		echo $status . "\n\n";
		fwrite($logfile, $status . "\n\n");
		sleep(1);
	}
	die();
}

$status = generateStatus($logfile);

if(searchArgs("notweet")) {
	fwrite($logfile, "Not tweeting -- notweet arg given.\n");
	echo $status;
	die();
}

require_once("TwitterAPIExchange.php");

$keys = json_decode(fread(fopen("pastebinkeys","r"),filesize("pastebinkeys")));
if($keys === NULL) {
	fwrite($logfile, "json failed or is NULL!\n");
}

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

if(strpos($twitter_output, "errors")) {
	fwrite($logfile, "ERROR! Twitter API response:\n"
	. json_encode(json_decode($twitter_output), JSON_PRETTY_PRINT));
}

fwrite($logfile, "\n");

echo "\n";
?>
