<?php

// read the webhook sent by LiveChat
$data = file_get_contents('php://input');
$data = json_decode($data);

// make sure the "chat_started" event occured
if ($data->event_type === 'chat_started')
{
    // read additional information about your visitor
    // from your internal database
	$email = "priti@gmail.com"; // $data->visitor->email;
	//$visitorDetails = $MyDatabase->query($email);

	// send this information to LiveChat apps
	$fields = array();
	$fields[] = (object)array(
		'name' => 'AC Manager',
		'value' => "pri testing"
	);
	$fields[] = (object)array(
		'name' => 'Position',
		'value' => "its position"
	);

	$curlFields = http_build_query(array(
		'license_id' => $data->license_id,
		'token' => $data->token,
		'id' => 'my-integration',
		'fields' => $fields
	));

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://api.livechatinc.com/visitors/'.$data->visitor->id.'/details');
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $curlFields);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-API-Version: 2'));
	$result = curl_exec($ch);
	curl_close($ch);
}