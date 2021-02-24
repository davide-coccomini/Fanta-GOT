<?
/*
* using curl
*/

$key = '';
$secret = '';
$api_endpoint = 'https://api.twitter.com/oauth2/token'; // endpoint must support "Application-only authentication"

// request token
$basic_credentials = base64_encode($key.':'.$secret);
$tk = curl_init('https://api.twitter.com/oauth2/token');
curl_setopt($tk, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$basic_credentials, 'Content-Type: application/x-www-form-urlencoded;charset=UTF-8'));
curl_setopt($tk, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
curl_setopt($tk, CURLOPT_RETURNTRANSFER, true);
$token = json_decode(curl_exec($tk));
curl_close($tk);

// use token
$data;
if (isset($token->token_type) && $token->token_type == 'bearer') {
	$br = curl_init($api_endpoint);
	curl_setopt($br, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token->access_token));
	curl_setopt($br, CURLOPT_RETURNTRANSFER, true);
	$data = curl_exec($br);
	curl_close($br);

  //print_r($data);
}

$tk = curl_init('https://api.twitter.com/1.1/account/verify_credentials.json?include_email=true');
$nonce = base64_encode(substr(uniqid(), 0, 6).uniqid().uniqid());
curl_setopt($tk, CURLOPT_HTTPHEADER, array('Authorization:',
																						'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
																						'OAuth oauth_consumer_key=""',
																						'oauth_nonce="'.$nonce.'"',
																						'oauth_signature_method="HMAC-SHA1"',
																						'oauth_timestamp="'.time().'"',
																						'oauth_token="'.$token->access_token.'"'));
curl_setopt($tk, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
curl_setopt($tk, CURLOPT_RETURNTRANSFER, true);

$user_data = json_decode(curl_exec($tk));
curl_close($tk);

?>
