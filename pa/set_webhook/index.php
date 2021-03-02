<?php
if( $_SERVER["HTTP_USER_AGENT"] == "GuzzleHttp/6.5.5 curl/7.51.0 PHP/5.6.31" ){
	if( $_SERVER["REQUEST_METHOD"] == "GET" ){
		exit;
	}
	if( $_SERVER["REQUEST_METHOD"] == "POST" ){
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://chatapi.viber.com/pa/set_webhook",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2_0,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => file_get_contents('php://input'),
			CURLOPT_HTTPHEADER => array(
				"Cache-Control: no-cache",
				"Content-Type: application/json",
				"X-Viber-Auth-Token: " . $_SERVER["HTTP_X_VIBER_AUTH_TOKEN"]
			)
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		if ($err) {
			echo "cURL Error #:" . $err;
		} else {
			echo $response;
		}
		exit;
	}
}
