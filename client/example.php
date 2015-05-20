<?php
$api = "http://private-3cd03-docgen.apiary-mock.com/api/v1";
$key = "API key";
$file = "data/template.docx";
$name = "name";

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "$api/template?name=" . urlencode($name));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents($file));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	"X-Auth: $key",
	"X-Upload-Content-Length: " . filesize($file),                                                                           
    "Content-Length: " . filesize($file)                    
));

$raw = curl_exec($ch);
$response = json_decode($raw);

curl_close($ch);

if (!$response) {
	throw new Exception("JSON Expected: $raw");
}

if (!$response->template_id) {
	throw new Exception("Expected template id. Got nothing");
}

$template_id = $response->template_id;
echo "Uploaded template, id: $template_id\n";

$data = array(
	array(
		'nadpis' => 'Nadpis dokumentu 1',
		'items' => array(
			array('name' => 'Item 1'),
			array('name' => 'Item 2'),
			array('name' => 'Item 3'),
		)
	),
	array(
		'nadpis' => 'Nadpis dokumentu 2',
		'items' => array(
			array('name' => 'Meti 1'),
			array('name' => 'Meti 2'),
			array('name' => 'Meti 3'),
			array('name' => 'Meti 4'),
			array('name' => 'Meti 5'),
			array('name' => 'Meti 6'),
			array('name' => 'Meti 7'),
		)
	)
);

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "$api/request");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Auth: $key"));

$raw = curl_exec($ch);
$response = json_decode($raw);

if (!$response) {
	throw new Exception("JSON Expected: $raw");
}

if (!$response->request_id) {
	throw new Exception("Expected request id. Got nothing");
}

$request_id = $response->request_id;
echo "Request created: $request_id.\n";

curl_setopt($ch, CURLOPT_POSTFIELDS, null);
curl_setopt($ch, CURLOPT_POST, FALSE);
curl_setopt($ch, CURLOPT_URL, "$api/request/$request_id");

while(true) {
	$raw = curl_exec($ch);
	$response = json_decode($raw, false);

	if (!$response) {
		throw new Exception("JSON Expected: $raw");
	}

	if ($response->status == "done") {
		echo "Request completed.\n";
		break;
	}
}

curl_setopt($ch, CURLOPT_URL, "$api/request/$request_id/download");

$raw = curl_exec($ch);
file_put_contents('downloaded.zip', $raw);

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
curl_setopt($ch, CURLOPT_URL, "$api/template/$template_id");

$raw = curl_exec($ch);
if ($raw) {
	throw new Exception("JSON Expected: $raw");
}

echo "Removed template.\n";

curl_close($ch);