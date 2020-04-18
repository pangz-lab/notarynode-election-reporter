<?php

function main() {
	
	$flag = trim(EXTERNAL_ARGS[1]);
	
	switch($flag) {
		case FLAGS["SUMMARY"]:
			logExec("[ EXEC ] >>> Summary Start");
			sendSummaryReport(
				FORMAT_SUMMARY_REPORT,
				DISCORD_WEBHOOK_URI,
				REPORTER_SETTING
			);
			logExec("[ EXEC ] >>> Summary End");
			
			break;
		case FLAGS["RECENT_VOTE"]:
			logExec("[ EXEC ] >>> Recent Vote Start");
			if(!hasNewVote()) {
				logExec("[ EXEC ] >>> Recent Vote End");
				exit();	
			}
			sendSummaryReport(
				FORMAT_SUMMARY_REPORT,
				DISCORD_WEBHOOK_URI,
				REPORTER_SETTING
			);
			
			sendNewVoteReport(
				FORMAT_NEWVOTE_REPORT,
				DISCORD_WEBHOOK_URI,
				REPORTER_SETTING
			);
			logExec("[ EXEC ] >>> Recent Vote End");
			break;
	}
}

function sendSummaryReport($reportFomat, $url, $setting) {
	$extractedData = extractPageData($setting);
	$summary       = createVotingSummary($extractedData["candidate_info"]);
	$rankingList   = createRankingList($extractedData["ar_ranking"]);	

	$reportSummaryData = array(
		'username' => 'James Jimenez',
		'content'  => sprintf($reportFomat, AS_OF, $summary, $rankingList)
	);

	return sendMessage($url, $reportSummaryData);
}

function sendNewVoteReport($reportFomat, $url, $setting) {
	$extractedData = extractPageData([$setting["CANDIDATE_INFO"]]);
	$result        = createRecentVoteReport($extractedData["candidate_info"]);
	$speaker       = SPK.SPK.SPK;
	

	$headerData = array(
		'username' => 'Andres Bautista',
		'content'  => $result["header"]
	);
	
	$reportSummaryData = array(
		'username' => 'Andres Bautista',
		'content'  => sprintf($reportFomat, $speaker, $result["asOf"], $result["summary"])
	);
	
	sendMessage($url, $reportSummaryData);
	sendMessage($url, $headerData);	
	return true;
}

function hasNewVote() {
	return (saveMostRecentVote([REPORTER_SETTING["CANDIDATE_INFO"]]) !== false);
}

function saveMostRecentVote($setting) {
	$extractedData  = extractPageData($setting);
	$recentVote     = $extractedData["candidate_info"]["recentVotes"][0] ?? [""];
	$recentVoteHash = md5(implode("XX", $recentVote));
	$currentHash 	= "";
	
	if(!file_exists(RECENT_VOTE_LOG)) {
		file_put_contents(RECENT_VOTE_LOG, $recentVoteHash);
		chmod(RECENT_VOTE_LOG, 0775);
		return true;
	}
	
	$currentHash = file_get_contents(RECENT_VOTE_LOG);
	if($currentHash !== $recentVoteHash) {
		return file_put_contents(RECENT_VOTE_LOG, $recentVoteHash);
	}
	
	return false;
}

function sendMessage($url, $data) {
	$payload = json_encode($data);
	$ch      = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLINFO_HEADER_OUT, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	 
	$result = curl_exec($ch);	 
	return curl_close($ch);
}

function logExec($data) {
	file_put_contents(EXEC_LOG, "[".date("M d,Y H:m:s")."]$data \n", FILE_APPEND);	
}