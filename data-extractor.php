<?php

function extractPageData(array $setting, bool $downloadPage = true) {
	$result     = [];
	$extractor  = "";
	$outputFile = "";
	foreach($setting as $currentTask) {
		$extractor  = $currentTask["extractor"];
		$outputFile = $currentTask["outputFile"];
		
		if($downloadPage) {
			getPage($currentTask["url"], $outputFile);
		}
		
		$result[$currentTask["name"]] = $extractor($outputFile);
	}
	
	return $result;
}

function extractRanking($file) {
	$doc = new DOMDocument();
	$doc->loadHTMLFile($file);
	$xpath = new \DOMXpath($doc);

	$paths = [
		"position" => "/html//div[@id='AR']/div[@class='card']//table/tbody/tr[%s]/td[1]",
		"name"     => "/html//div[@id='AR']/div[@class='card']//table/tbody/tr[%s]/td[2]",
		"votes"    => "/html//div[@id='AR']/div[@class='card']//table/tbody/tr[%s]/td[4]/span[@class='text-success']",
	];

	$ranking = [];
	foreach($paths as $key => $currentPath) {
		for($x = 1; $x <= 12; $x++) {
			$path = sprintf($currentPath, $x);
			$ranking[$x][$key] = trim($xpath->query($path)[0]->nodeValue);
		}
	}
	return $ranking;
}

function extractUserInfo($file) {
		
	$doc     	    = new DOMDocument();
	$voterUri       = "https://vote2020.komodod.com";
	$casterLinkPath = "//table[@id='recent-votes']/tbody/tr[1]//a";
	$doc->loadHTMLFile($file);
	$xpath   = new \DOMXpath($doc);
	$result  = [];
	$temp    = [];
	$paths = [
		"position"    => "/html//div[@class='container-scroller']/div[@class='container-fluid page-body-wrapper']/div[@class='main-panel']/div[@class='content-wrapper']/div[@class='row']/div[@class='col-md-5 grid-margin grid-margin-md-0 stretch-card']//span[@class='h6 text-light']",
		"totalVotes"  => "/html//div[@class='container-scroller']/div[@class='container-fluid page-body-wrapper']/div[@class='main-panel']/div[@class='content-wrapper']/div[@class='row']//div[@class='border-top pt-3 text-center']/div[@class='row']/div[2]/h6[@class='text-light']",
		"votesToday"  => "/html//div[@class='container-scroller']/div[@class='container-fluid page-body-wrapper']/div[@class='main-panel']/div[@class='content-wrapper']/div[@class='row']//div[@class='border-top pt-3 text-center']/div[@class='row']/div[3]/h6[@class='text-light']",
		"recentVotes" => "//table[@id='recent-votes']/tbody/tr"
	];
	$trim = function($item) {
		return (trim($item) !== '');
	};
	
	foreach($paths as $key => $path) {
		if($key == 'recentVotes') {
			for($x = 0; $x <= 5; $x++) {
				$temp = explode("\n", trim($xpath->query($path)[$x]->textContent));
				$temp[] = $voterUri.$xpath->query(sprintf($casterLinkPath, $x))[0]->getAttribute("href");
				
				$temp = array_filter($temp, $trim);
				$result[$key][$x] = $temp;
			}
			continue;
		}
		$result[$key] = $xpath->query($path)[0]->textContent;
	}
	
	return $result;
}

function getPage($url, $outputFile) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

	curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

	$headers   = array();
	$headers[] = 'Authority: vote2020.komodod.com';
	$headers[] = 'Cache-Control: max-age=0';
	$headers[] = 'Upgrade-Insecure-Requests: 1';
	$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36';
	$headers[] = 'Sec-Fetch-Dest: document';
	$headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
	$headers[] = 'Sec-Fetch-Site: none';
	$headers[] = 'Sec-Fetch-Mode: navigate';
	$headers[] = 'Sec-Fetch-User: ?1';
	$headers[] = 'Referer: https://vote.dexstats.info/';
	$headers[] = 'Accept-Language: en-US,en;q=0.9,fil;q=0.8,ja;q=0.7,fr;q=0.6,und;q=0.5';
	$headers[] = 'Cookie: __cfduid=d9648a9bfb77479a02d52a4dd4c45d9f61587013817; _ga=GA1.2.418247541.1587013822; _gid=GA1.2.754777869.1587013822; _gat_gtag_UA_152646601_1=1';
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$data = curl_exec($ch);
	if (curl_errno($ch)) {
		return 'Error:' . curl_error($ch);
	}
	curl_close($ch);
	return file_put_contents($outputFile, $data);
}