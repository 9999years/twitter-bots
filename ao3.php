<?php

ini_set("display_errors", 1);
error_reporting(E_ERROR | E_PARSE);

$rawhtml = file_get_contents("https://archiveofourown.org/works");

$dom = new DOMDocument();

$dom->loadHTML($rawhtml);

$xpath = new DOMXpath($dom);

$tags = $xpath->query("//a[@class='tag']");

foreach($tags as $tag) {
	var_dump($tag);
}

function generateTweet() {
}

echo generateTweet() . "\n";
?>
