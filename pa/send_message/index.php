<?php

function send($message){
	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => "https://chatapi.viber.com/pa/send_message",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2_0,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => $message,
		CURLOPT_HTTPHEADER => array(
			"Cache-Control: no-cache",
			"Content-Type: application/json",
			"X-Viber-Auth-Token: " . file_get_contents('../../token')
		)
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	if ($err) {
		//echo "cURL Error #:" . $err;
		file_put_contents('viber.log', $err);
	} else {
		//echo $response;
		file_put_contents('viber.log', $response);
	}
}

function readFromFile($file){
	if( !file_exists($file) ) file_put_contents($file, "{}");
	$message = file_get_contents($file);
	//file_put_contents($file, "{}");
	return $message;
}

if( $_SERVER["HTTP_USER_AGENT"] == "GuzzleHttp/6.5.5 curl/7.51.0 PHP/5.6.31" ){
	if( $_SERVER["REQUEST_METHOD"] == "GET" ){
		$message = readFromFile('viber.json');
		header('Content-Type: application/json; charset=UTF-8');
		die($message);
	}
	if( $_SERVER["REQUEST_METHOD"] == "POST" ){
		file_put_contents('localhost.json', file_get_contents('php://input'));
		$message = readFromFile('localhost.json');
		send($message);
		exit;
	}
}else{
	if( $_SERVER["REQUEST_METHOD"] == "GET" ){
		exit;
	}
	if( $_SERVER["REQUEST_METHOD"] == "POST" ){
		file_put_contents('viber.json', file_get_contents('php://input'));
		exit;
	}
}
