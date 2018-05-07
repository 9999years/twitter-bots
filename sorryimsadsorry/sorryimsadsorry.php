<?php
//worst practice style guide

require_once("../common.php");

//$arrow = "\u{2190}"; //left arrow

$startTime = time();
mt_srand($startTime);

$logfile = fopen("sad.log","a");
fwrite($logfile,"\n" . date("d F Y, H:i:s") . " (" . $startTime . "): " . __FILE__ . " run,");

if(rand(0,6) != 0 && !searchArgs("force")) {
	fwrite($logfile, " status not generated.");
	die();
}

if(searchArgs("force")) {
	fwrite($logfile, " status FORCED. ");
} else {
	fwrite($logfile, " status generated. ");
}

$emotions = array(
	 "upset"
	,"sad"
	,"lonely"
	,"depressed"
	,"fuck"
	,"realy not feeling so good"
	,"bad"
	,"not good"
	,"the opposite of feeling good"
	,"agitated"
	,"confused"
	,"disconcerted"
	,"dismayed"
	,"disordered"
	,"disquieted"
	,"distressed"
	,"ill"
	,"hurt"
	,"low"
	,"muddled"
	,"rattled"
	,"shocked"
	,"sick"
	,"troubled"
	,"unsettled"
	,"worried"
	,"torn up"
	,"antsy"
	,"apprehensive"
	,"jittery"
	,"jumpy"
	,"disillusioned"
	,"disheartened"
	,"scared"
	,"frightened"
	,"nonplussed"
	,"terrified"
	,"super bad"
	,"real shit"
	,"really shitty"
	,"bitter"
	,"dismal"
	,"heartbroken"
	,"melancholy"
	,"mournful"
	,"pessimistic"
	,"sorrowful"
	,"sorry"
	,"wistful"
	,"blue"
	,"cheerless"
	,"dejected"
	,"despairing"
	,"despondent"
	,"disconsolate"
	,"distressed"
	,"doleful"
	,"down"
	,"downcast"
	,"gloomy"
	,"glum"
	,"grief-stricken"
	,"heartsick"
	,"hurt"
	,"hurting"
	,"in grief"
	,"morbid"
	,"morose"
	,"sour"
	,"astringent"
);


$much = array(
	 "so"
	,"so"
	,"so"
	,"so"
	,"so so"
	,"sooo"
	,"soo"
	,"soooo"
	,"sooooo"
	,"terribly"
	,"intensely"
	,"badly"
	,"sadly"
	,"angrily"
	,"apprehensively"
	,"really"
	,"real"
	,"very"
	,"authentically"
	,"undoubtedly"
	,"indubitably"
	,"extremely"
	,"remarkably"
	,"unusually"
	,"infinitely"
	,""
	,""
	,""
	,""
	,""
	,""
);

$bads = array(
	 "shitty"
	,"terrible"
	,"bad"
	,"no-good"
	,"horrible"
	,"scary"
	,"terrifying"
	,"depressing"
	,"troubling"
);

$insults = array(
	 "%not good at anything"
	,"%unskilled"
	,"%useless"
	,"%horrible"
	,"bad person!"
	,"terrible friend"
	,"%oversensitive"
	,"%\u{201c}troubled\u{201d}"
	,"%shitty"
	,"%bad"
	,"piece of shit"
	,"%stupid"
	,"idiot"
	,"horrible jerk"
	,"melon"
	,"pleb"
	,"loser"
	,"prole"
	,"amateur"
	,"arse"
	,"arselicker"
	,"ass"
	,"ass-kisser"
	,"asshole"
	,"baby"
	,"bandit"
	,"bastard"
	,"beginner"
	,"butt"
	,"chauvinist"
	,"con-man"
	,"creep"
	,"cretin"
	,"daywalker"
	,"deathlord"
	,"desperado"
	,"devil"
	,"dickhead"
	,"dog"
	,"donkey"
	,"dreamer"
	,"dufus"
	,"egoist"
	,"fake"
	,"fibber"
	,"fish"
	,"flake"
	,"frog"
	,"fuck"
	,"fucker"
	,"goose"
	,"grumpy"
	,"jackass"
	,"liar"
	,"looser"
	,"monster"
	,"nobody"
	,"pig"
	,"reject"
	,"shark"
	,"skunk"
	,"slimer"
	,"snail"
	,"snake"
	,"snob"
	,"square"
	,"stinker"
	,"swindler"
	,"wanker"
	,"wierdo"
	,"witch"
	,"%worthy of a thousand deaths" //thanks luther
	,"%foolish and ignorant"
	,"heretic"
);

$sorries = array(
	 "sorry"
	,"sorryy"
	,"sooorry"
	,"sorry, sorry,"
);

$templates = array(
	 "im just feeling {{MUCH}} {{EMOTIONS}}"
	,"im just feeling {{MUCH}} {{EMOTIONS}}, {{SORRY}}"
	,"im just {{MUCH}} {{EMOTIONS}}, {{SORRY}}, sorry"
	,"{{SORRY}}, im just {{MUCH}} {{EMOTIONS}}"
	,"it\u{2019}s just {{ARTICLE}} {{MUCH}} {{BADS}} day"
	,"\u{2190} {{EMOTIONS}}"
	,"\u{2190} {{EMOTIONS}}, {{SORRY}}"
	,"\u{2190} {{INSULTS}}"
	,"\u{2190} {{INSULTS}}, {{SORRY}}"
	,"{{MUCH}} {{EMOTIONS}}"
	,"{{SORRY}}, {{MUCH}} {{EMOTIONS}}"
	,"{{MUCH}} {{EMOTIONS}}, {{SORRY}}"
	,"im just {{ARTICLE}} {{INSULTS}}"
	,"im just {{ARTICLE}} {{INSULTS}}, {{SORRY}}"
	,"im {{MUCH}} {{BADS}}"
	,"{{INSULTS}}! {{INSULTS}}! {{INSULTS}}!"
	,"{{INSULTS}}! {{INSULTS}}! {{INSULTS}}! {{SORRY}}"
	,"please, im {{MUCH}} {{EMOTIONS}}, just let me sleep"
	,"im {{MUCH}} {{EMOTIONS}}, i just want to go to bed"
	,"im {{MUCH}} {{EMOTIONS}} and i really dont want to talk about it"
	,"please just leave my {{EMOTIONS}} self alone"
	,"im nothing more than {{ARTICLE}} {{INSULTS}}"
	,"im such {{ARTICLE}} {{INSULTS}}"
	,"i deserve this, even though im {{MUCH}} {{EMOTIONS}}"
	,"im really {{SORRY}}, im just {{MUCH}} {{EMOTIONS}}"
	,"im {{MUCH}} {{SORRY}} to trouble you"
	,"today is {{MUCH}} {{ARTICLE}} \u{201c}{{EMOTIONS}}\u{201d} day, you know?"
);

function generateSmiley() {
	if(rand(0,2) == 0) {
		$eyes = ":;=B|";
		$cries = "`'\u{2019}\u{2018}\u{201d}\u{201c}*";
		$noses = "^-+~";
		$mouths = "({|\\/<$#XPO";
		$smiley = randomCharacter($eyes);
		if(rand(0,1) == 0) {
			$smiley .= randomCharacter($cries);
		}
		if(rand(0,1) == 0) {
			$smiley .= randomCharacter($noses);
		}
		$smiley .= randomCharacter($mouths);
		return $smiley;
	} else if(rand(0,1) == 0) {
		$leftEyes = array("\u{3b}","\u{ff1b}","\u{3a9}","\u{ff49}","\u{ca5}","\u{b87}","\u{2c3}","\u{2c3}","\u{ff89}","\u{b0}","\u{2565}","\u{3112}","\u{54}","\u{2f}","\u{2530}","\u{252c}","\u{ff0f}","\u{ff89}","\u{256f}","\u{d3}","\u{2018}","\u{3064}","\u{2267}","\u{753}","\u{4e2a}","\u{2162}","\u{203b}");
		$rightEyes = array("\u{3b}","\u{ff1b}","\u{3a9}","\u{ff49}","\u{ca5}","\u{b87}","\u{2c2}","\u{2c2}","\u{ff40}","\u{b0}","\u{2565}","\u{3112}","\u{54}","\u{ff3c}","\u{2530}","\u{252c}","\u{ff3c}","\u{ff3c}","\u{2570}","\u{d2}","\u{2018}","\u{2282}","\u{2266}","\u{753}","\u{4e2a}","\u{2162}","\u{203b}");
		$mouths = array("\u{3078}","\u{30a7}","\u{d7}","\u{3142}","\u{fe35}","\u{b3}","\u{26a}","\u{434}","\u{317f}","\u{20}","\u{33c}","\u{20}","\u{293}","\u{2302}","\u{277}","\u{1d54}","\u{5f}","\u{fe4f}","\u{5e}","\u{3c9}");
		$cheeks = array("\u{2a}","\u{e51}","\u{e50}","\u{9f9}","\u{23}","\u{2726}","\u{25cf}","\u{40}","\u{25cd}","\u{2205}","\u{203b}");
		$leftArms = array("\u{309c}\u{30fb}\u{ff0e}","\u{3078}","\u{250f}","\u{ab}","\u{2208}","\u{2282}","\u{2da}\u{2027}\u{ba}\u{b7}");
		$rightArms = array("\u{ff61}\u{ff1a}\u{ff9f}","\u{28b}","\u{2513}","\u{bb}","\u{220b}","\u{2283}","\u{2027}\u{ba}\u{b7}\u{2da}");
		$tears = array("\u{316}","\u{317}","\u{318}","\u{319}","\u{31a}","\u{31b}","\u{31c}","\u{31d}","\u{31e}","\u{31f}","\u{320}","\u{321}","\u{322}","\u{323}","\u{324}","\u{325}","\u{326}","\u{327}","\u{328}","\u{329}","\u{32a}","\u{32b}","\u{32c}","\u{32d}","\u{32e}","\u{32f}","\u{330}","\u{331}","\u{332}","\u{333}","\u{339}","\u{33a}","\u{33b}","\u{33c}","\u{347}","\u{348}","\u{349}","\u{34d}","\u{34e}","\u{353}","\u{354}","\u{355}","\u{359}","\u{35a}");

		$leftSides = array("\u{28}","\u{5b}","\u{7b}","\u{2045}","\u{2308}","\u{230a}","\u{2768}","\u{276a}","\u{276c}","\u{276e}","\u{2770}","\u{2772}","\u{2774}","\u{27c5}","\u{27e6}","\u{27e8}","\u{27ea}","\u{27ec}","\u{2983}","\u{2985}","\u{298b}","\u{298d}","\u{298f}","\u{2991}","\u{2993}","\u{2995}","\u{2997}","\u{29d8}","\u{29da}","\u{3008}","\u{300a}","\u{300c}","\u{300e}","\u{3010}","\u{3014}","\u{3016}","\u{3018}","\u{301a}","\u{fe59}","\u{fe5b}","\u{fe5d}","\u{ff08}","\u{ff3b}","\u{ff5b}","\u{ff5f}","\u{fd3e}","\u{ff62}");
		$rightSides = array("\u{29}","\u{5d}","\u{7d}","\u{2046}","\u{2309}","\u{230b}","\u{2769}","\u{276b}","\u{276d}","\u{276f}","\u{2771}","\u{2773}","\u{2775}","\u{27c6}","\u{27e7}","\u{27e9}","\u{27eb}","\u{27ed}","\u{2984}","\u{2986}","\u{298c}","\u{298e}","\u{2990}","\u{2992}","\u{2994}","\u{2996}","\u{2998}","\u{29d9}","\u{29db}","\u{3009}","\u{300b}","\u{300d}","\u{300f}","\u{3011}","\u{3015}","\u{3017}","\u{3019}","\u{301b}","\u{fe5a}","\u{fe5c}","\u{fe5e}","\u{ff09}","\u{ff3d}","\u{ff5d}","\u{ff60}","\u{fd3f}","\u{ff63}");

		$eyesIndex = rand(0, count($leftEyes));
		$armsIndex = rand(0, count($leftArms));
		$sidesIndex = rand(0, count($leftSides));
		$leftEye = $leftEyes[$eyesIndex];
		$rightEye = $rightEyes[$eyesIndex];
		$mouth = pickRandom($mouths);
		$cheek = rand(0,1) ? pickRandom($cheeks) : "";
		$isArms = rand(0,1);
		$leftArm = $isArms ? $leftArms[$armsIndex] : "";
		$rightArm = $isArms ? $rightArms[$armsIndex] : "";
		$leftSide = $leftSides[$sidesIndex];
		$rightSide = $rightSides[$sidesIndex];

		$tear = "";
		if(rand(0,1)) { //tears
			$j = rand(1,5);
			for($i = 0; $i < $j; $i++) {
				$tear .= pickRandom($tears);
			}
		}

		return $leftArm
			. $leftSide
			. $cheek
			. $leftEye
			. $tear
			. $mouth
			. $rightEye
			. $tear
			. $cheek
			. $rightSide
			. $rightArm;
	} else { //emoji
		$emoji = array("\u{1f610}","\u{1f611}","\u{1f636}","\u{1f623}","\u{1f625}","\u{1f62e}","\u{1f62f}","\u{1f62b}","\u{1f634}","\u{1f612}","\u{1f613}","\u{1f614}","\u{1f615}","\u{1f632}","\u{2639}","\u{1f641}","\u{1f616}","\u{1f61e}","\u{1f61f}","\u{1f624}","\u{1f622}","\u{1f62d}","\u{1f626}","\u{1f627}","\u{1f628}","\u{1f629}","\u{1f62c}","\u{1f630}","\u{1f621}","\u{1f620}","\u{1f640}","\u{1f63f}","\u{1f63e}","\u{1f922}","\u{1f927}");
		return pickRandom($emoji);
	}
}
/*
 *function chance($percent) {
 *    return random(0,100)*$percent > 
 *}
 */

function generatePunctuation($amount) {
	$punctuation = array(
		 "!"
		,"?"
		,","
		,"."
		);
	$result = "";
	for($i = 0; $i < $amount; $i++) {
		if(rand(0,10) != 0) { //inclusive
			$result .= ",";
		} else if(rand(0,2) == 0) {
			$result .= "?,."[rand(0,2)];
		} else {
			$result .= "/\\-=+~!@#%^&*()"[rand(0,16)];
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

function typo($input, $chance) {
	for($i = 0; $i < mb_strlen($input); $i++) {
		if(rand(0,100) > $chance) {
			continue;
		}
		$len = mb_strlen($input);
		//$pos = rand(0,$len-1);
		$pos = $i;
		switch(rand(0,8)) {
			case 0:
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
				$input = mb_substr($input, 0, $pos) . 
					mb_substr($input, $pos+1, 1) .
					mb_substr($input, $pos, 1) .
					mb_substr($input, $pos+2);
				break;
			case 6:
				$input = mb_substr($input, 0, $pos) . mb_substr($input, $pos, 1) . mb_substr($input, $pos);
				break;
			case 7:
				$input = mb_substr($input, 0, $pos) . mb_substr($input, $pos+1);
				break;
			case 8:
				$input = mb_substr($input, 0, $pos) . generatePunctuation(rand(1,3)) . mb_substr($input, $pos);
				break;
		}
	}
	return $input;
}

function formatString($input) {
	global $emotions, $bads, $much, $insults, $sorries;
	$replacements = array(
		 "{{MUCH}}" => pickRandom($much)
		,"{{EMOTIONS}}" => pickRandom($emotions)
		,"{{INSULTS}}" => pickRandom($insults)
		,"{{BADS}}" => pickRandom($bads)
		,"{{SORRY}}" => pickRandom($sorries)
	);
	$keys = array_keys($replacements);
	for($i = 0; $i < count($replacements); $i++) {
		$input = mb_ereg_replace($keys[$i], $replacements[$keys[$i]], $input);
	}
	for($i = 0; $i < mb_substr_count($input, "{{ARTICLE}}"); $i++) {
		//replace "{{ARTICLE}} boy" with "a boy"
		$pos = mb_strpos($input, "{{ARTICLE}}");
		//"{{ARTICLE}}" is 11 chars long
		mb_ereg("[a-zA-Z%]", mb_substr($input, $pos+12), $nextLetter);
		$nextLetter = $nextLetter[0];
		//echo $nextLetter . "\n\n";
		$article = "a ";
		if($nextLetter == "a" || $nextLetter == "e" || $nextLetter == "i" || $nextLetter == "o" || $nextLetter == "u") {
			$article = "an ";
		} else if($nextLetter == "%") { //opt out
			$article = "";
		}
		$input = mb_substr($input, 0, $pos) . $article . mb_substr($input, $pos+12);
	}
	$input = mb_ereg_replace("%", "", $input); //get rid of %s

	return $input;
}

function generateStatus() {
	global $templates;
	$result = typo(formatString(pickRandom($templates)),2) . generatePunctuation(rand(0,2));
	if(rand(0,1) == 0) {
		$result .= generateSmiley();
	}

	return $result;
}

if(searchArgs("test")) {
	fwrite($logfile, "TEST100.");
	for($i = 0; $i < 100; $i++) {
		echo mb_substr(generateStatus(),0,140) . "\n\n";
	}
	die();
}

$status = mb_substr(generateStatus(),0,140);

if(searchArgs("silent")) {
	fwrite($logfile, "Output SILENCED.");
	echo $status . "\n\n";
	die();
}

$twitter = twitterfromkeysfile("sorryimsadsorry.json");
fwrite($logfile, "Output: " . $twitter->buildOauth("https://api.twitter.com/1.1/statuses/update.json", "POST")
	->setPostfields(array(
		"status" => mb_substr(generateStatus(),0,140)
	))
	->performRequest());
fwrite($logfile, "\n");
?>
