<?php
define("TEMP_DIR", BASE_DIR."temp_file".DS);
define("RECENT_VOTE_LOG", TEMP_DIR."recentvote.log");
define("EXEC_LOG", BASE_DIR."exec_log.log");
define("FLAGS", [
	"SUMMARY" => "--send-summary",
	"RECENT_VOTE" => "--send-recent-vote",
]);

define("SPK", ":loudspeaker:");
define("AS_OF", date("H:i")." PST 🗓 :flag_ph: ");
define("WORLD_RESULT_URI", "https://vote2020.komodod.com/");
define("DUDEZMOBI_RANK_URI", "https://vote2020.komodod.com/candidate/dudezmobi_AR");
// define("DISCORD_WEBHOOK_URI", 'https://discordapp.com/api/webhooks/700222471017594921/z7452sohRXZhRqGJUHCPtsJhCR00ANQkAXA0IolD3psCogdmymwJXAJQa8Dpp3ggGHNW');
define("DISCORD_WEBHOOK_URI", 'https://discordapp.com/api/webhooks/700667528048935012/_PuZpriAUWFOtVjWTaJvcopkI82djQikaRWhNywTRJ-b4y0CJfE4jxPHx_BEMMjSWo4S');
define("FORMAT_SUMMARY_REPORT", "📈 📈 📈 📈 📈 📈 📈 \n\n**Dudezmobi NotaryNode Election Report⛳⛳⛳**\n as of **%s** %s %s");
define("FORMAT_NEWVOTE_REPORT", "🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥🔥\n\n**Recent Vote Recorded %s **\nReceived **%s** %s \n");

define("REPORTER_SETTING", [
	"AR_RANKING" => [
		"name" => "ar_ranking",
		"url" => 'https://vote2020.komodod.com/',
		"outputFile" => TEMP_DIR."dms-ar-ranking2.html",
		"extractor" => "extractRanking"
	],
	"CANDIDATE_INFO" => [
		"name" => "candidate_info",
		"url" => 'https://vote2020.komodod.com/candidate/dudezmobi_AR',
		"outputFile" => TEMP_DIR."dms2.html",
		"extractor" => "extractUserInfo"
	]
]);

define("FORMAT_LIST", [
	"RANKING" => "\n[AR Candidate Ranking](".WORLD_RESULT_URI.")\n%s",
	"VOTE_SUMMARY" => "\n\n**[🏆 Ranking : %s](".DUDEZMOBI_RANK_URI.")** \n ```Total Votes: %s \nVotes Today: %s```",
	"RECENT_VOTES" => "\n >>> Recent Votes\n%s",
	"RECENT_VOTE_SUMMARY" => "\n```Total Votes:``` ||%s|| \n```Votes Today:``` ||%s||\n\n",
]);
