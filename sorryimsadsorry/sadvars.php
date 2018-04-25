<?php
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
		 "not good at anything"
		,"just bad"
		,"unskilled"
		,"useless"
		,"horrible"
		,"bad person!"
		,"terrible friend"
		,"oversensitive"
		,"\u{201c}troubled\u{201d}"
		,"shitty"
		,"bad"
		,"piece of shit"
		,"stupid"
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
		,"worthy of a thousand deaths" //thanks luther
		,"foolish and ignorant"
		,"heretic"
	);

	$templates = array(
		 "im just feeling {{MUCH}} {{EMOTIONS}}"
		,"it\u{2019}s just {{ARTICLE}} {{MUCH}} {{BADS}} day"
		,"\u{2190} {{EMOTIONS}}"
		,"\u{2190} {{INSULTS}}"
		,"{{MUCH}} {{EMOTIONS}}"
		,"im just {{ARTICLE}} {{INSULTS}}"
	);

	function pickRandom($input) {
		return $input[rand(0,sizeof($input)-1)];
	}

	function formatString($input) {
		global $emotions, $bads, $much, $insults;
		$replacements = array(
			 "{{MUCH}}" => pickRandom($much)
			,"{{EMOTIONS}}" => pickRandom($emotions)
			,"{{INSULTS}}" => pickRandom($insults)
			,"{{BADS}}" => pickRandom($bads)
		);
		$keys = array_keys($replacements);
		for($i = 0; $i < count($replacements); $i++) {
			$input = mb_ereg_replace($keys[$i], $replacements[$keys[$i]], $input);
		}
		for($i = 0; $i < mb_substr_count($input, "{{ARTICLE}}"); $i++) {
			//replace "{{ARTICLE}} boy" with "a boy"
			$pos = mb_strpos($input, "{{ARTICLE}}");
			//"{{ARTICLE}}" is 11 chars long
			$nextLetter = mb_substr($input, $pos+12, 1);
			//echo $nextLetter . "\n";
			$article = "a";
			if($nextLetter == "a" || $nextLetter == "e" || $nextLetter == "i" || $nextLetter == "o" || $nextLetter == "u") {
				$article = "an";
			}
			$input = mb_substr($input, 0, $pos) . $article . " " . mb_substr($input, $pos+12);
		}

		return $input;
	}

	echo formatString(pickRandom($templates)) . "\n\n";

?>
