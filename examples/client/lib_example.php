<?php
require_once('lib/Api.php');

$root = dirname(__FILE__);
$key = "9294e029e9fefcb8a0e4f6914ab8bf47";
$url = "http://localhost/document-generator/web/public/api/v1";
$template_file = "$root/data/template.docx";
$template_name = "Test template";
$data_file = "$root/data/data.json";
$target_file = "$root/downloaded.zip";

$api = new Docgen\Api($url, $key);

echo "Uploading template ... ";
$template_id = $api->uploadTemplate($template_name, $template_file);
echo "Template uploaded\n";
echo "Sending request ... ";
$request_id = $api->sendRequest($template_id, json_decode(file_get_contents($data_file)));
echo "Request send\n";

echo "Waiting for results ... ";
while(true) {
	$data = $api->getRequestInfo($request_id);
	if ($data->status == 'done') {
		echo "Request is done\n";
		break;
	}
}

echo "Downloading result ... ";
$api->downloadRequest($request_id, $target_file);
echo "Result downloaded\n";
echo "Result saved to '$target_file'\n";