<?php
set_time_limit(0);

$root = dirname(__FILE__);

//Basic values - url of api
$api = "http://localhost/htdocs/document-generator/web/public/api/v1";
//And your API KEY
$key = "4f4a0022f89220add423dbc36228e826";

//Path to DOCX template file
$template_file = "$root/data/template.docx";
//Path to JSON containing documents data
$data_file = "$root/data/data.json";
//Path where should generated documents be saved
$result_file = "$root/downloaded.zip";

//Generate template name from filename
$name = pathinfo($template_file, PATHINFO_FILENAME);

/*
 * UPLOAD TEMPLATE TO SERVER
 */
 
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "$api/template?name=" . urlencode($name));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents($template_file));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	"X-Auth: $key",
	"Accept: application/json",
	"Content-type: application/json"
));

echo "Uploading template ...  ";

$raw = curl_exec($ch);
$response = json_decode($raw);

if (!$response) {
	throw new Exception("JSON Expected: $raw");
}

if (isset($response->error)) {
	throw new Exception("Error: $response->error");
}

if (!isset($response->template_id)) {
	throw new Exception("Expected template id. Got nothing");
}

$template_id = $response->template_id;
echo "Template uploaded.\n";

/*
 * CREATE REQUEST
 */

//Request data
$data = array(
	'template_id' => $template_id,
	'type' => 'docx',
	'data' => json_decode(file_get_contents($data_file), true)
);


curl_setopt($ch, CURLOPT_URL, "$api/request");
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

echo "Sending request ... ";

$raw = curl_exec($ch);
$response = json_decode($raw);

if (!$response) {
	throw new Exception("JSON Expected: $raw");
}

if (isset($response->error)) {
	throw new Exception("Error: $response->error");
}

if (!isset($response->request_id)) {
	throw new Exception("Expected request id. Got nothing");
}

$request_id = $response->request_id;
echo "Request created.\n";

/**
 * WAITING FOR RESULT
 */

echo "Waiting for completion ... ";
 
curl_setopt($ch, CURLOPT_POSTFIELDS, null);
curl_setopt($ch, CURLOPT_POST, FALSE);
curl_setopt($ch, CURLOPT_URL, "$api/request/$request_id");

while(true) {
	$raw = curl_exec($ch);
	$response = json_decode($raw, false);

	if (!$response) {
		throw new Exception("JSON Expected: $raw");
	}
	
	if (isset($response->error)) {
		throw new Exception("Error: $response->error");
	}

	if (!isset($response->status)) {
		throw new Exception("Expected request status. Got nothing");
	}

	if ($response->status == "done") {
		echo "Request completed.\n";
		break;
	}
	
	sleep(1);
}

curl_setopt($ch, CURLOPT_URL, "$api/request/$request_id/download");

$raw = curl_exec($ch);
file_put_contents($result_file, $raw);

echo "Result saved to $result_file.\n";

curl_close($ch);