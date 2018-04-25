<?php
require_once("TwitterAPIExchange.php");
$keys = json_decode(fread(fopen("investkeys","r"),filesize("investkeys")));
$settings = array(
	'oauth_access_token' => $keys->access_token,
	'oauth_access_token_secret' => $keys->access_token_secret,
	'consumer_key' => $keys->consumer_key,
	'consumer_secret' => $keys->consumer_secret
);
$twitter = new TwitterAPIExchange($settings);
echo $twitter->buildOauth("https://api.twitter.com/1.1/statuses/update.json", "POST")
	->setPostfields(array(
		"status" => json_decode('"\u2014"')
	))
	->performRequest();
?>
