<?php

function formatVote($d, $dec = 5) {
	return number_format($d, $dec, '.', ',');
}

function addNumberSuffix($n) {
	$num    = $n;
	$suffix = ["st","nd","rd"];
	$n      = substr($n,-1);
	return $num.(($n > 3)? "th": $suffix[$n-1]);
}

function createRankingList($data) {
	$result  = "";
	$prefix  = "  ";
	$ranking = FORMAT_LIST["RANKING"];
	foreach($data as $dataArray) {
		$result .= $prefix."[".str_pad($dataArray['position'], 2, ' ')."] ".
		str_pad($dataArray['name'], 15, ' ')." ".
		"💰".formatVote($dataArray['votes'])."\n";
	}
	
	return sprintf($ranking, "```".$result."```");
}

function createVotingSummary($data) {
	$i      = 1;
	$result = "";
	$votes  = "";
	$temp   = "";
	$summaryFormat     = FORMAT_LIST["VOTE_SUMMARY"];
	$recentVotesFormat = FORMAT_LIST["RECENT_VOTES"];
	
	$result = sprintf(
		$summaryFormat,
		addNumberSuffix($data['position']),
		formatVote($data['totalVotes']),
		formatVote($data['votesToday'])
	);
	
	foreach($data['recentVotes'] as $key => $currentVote) {		
		$votes .= "``` [$i] ".str_pad(trim($currentVote[0]), 12,' ')." - ".
		"💰".str_pad(formatVote($currentVote[6], 2), 10,' ')."``` ".
		"[".str_pad(trim($currentVote[3]), 5,' ')."](".$currentVote[7].")".		
		"\n";
		$i++;
	}
	
	$result .= sprintf(
		$recentVotesFormat, $votes
	);
	
	return $result;
}

function createRecentVoteReport($data) {

	$result    = [];
	$votes     = "";
	$temp      = "";
	$rankLink      = DUDEZMOBI_RANK_URI;
	$headerFormat  = "%s";	
	$summaryFormat = FORMAT_LIST["RECENT_VOTE_SUMMARY"];
	$recentVote    = $data['recentVotes'][0];
	$latestVote    = formatAsNumberEmoji(formatVote($recentVote[6], 4),false);	
	
	$result["summary"] = sprintf(
		$summaryFormat,		
		formatAsNumberEmoji(formatVote($data['totalVotes'])),
		formatAsNumberEmoji(formatVote($data['votesToday']))
	);
	
	$result["header"] = sprintf(
		$headerFormat,
		$latestVote
	);
	
	$result["asOf"] = $recentVote[0];
	
	return $result;
}

function formatAsNumberEmoji($value, $rawSymb = true) {
	
	if(empty($value)) {return $value;}
	$result = "";
	$value  = str_split($value);
	$replacement = [
		"1" => ":one:",
		"2" => ":two:",
		"3" => ":three:",
		"4" => ":four:",
		"5" => ":five:",
		"6" => ":six:",
		"7" => ":seven:",
		"8" => ":eight:",
		"9" => ":nine:",
		"0" => ":zero:",
		"." => ($rawSymb)? " . " : ":small_orange_diamond:",
		"," => ($rawSymb)? " , " : ":small_blue_diamond:",
	];
	$formatter = function($value, $rep) {
		return $rep[$value];
	};
	
	foreach($value as $char) {
		$result .= $formatter($char, $replacement);
	}
	return $result;
}