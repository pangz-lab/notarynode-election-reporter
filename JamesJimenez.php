<?php
date_default_timezone_set('Asia/Manila');
libxml_use_internal_errors(true);
define("DS", "/");
define("BASE_DIR", dirname(__FILE__).DS);
define("EXTERNAL_ARGS", $argv);
require_once(BASE_DIR."config.php");
require_once(BASE_DIR."data-extractor.php");
require_once(BASE_DIR."data-formatter.php");
require_once(BASE_DIR."report-sender.php");

try {
	main();
} catch(Exception $e) {
	logExec("[ ERROR ]!!!".$e->getMessage());
}