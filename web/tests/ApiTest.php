<?php

class ApiTest extends TestCase {	
	protected $api = "http://localhost/htdocs/document-generator/web/public/api/v1";
	protected $key;
	
	protected function getApiKey() {
		if (!$this->key)
			$this->key = App\User::whereNotNull('api_key')->firstOrFail()->api_key;
		return $this->key;
	}
	
	protected function apiRequest($url, $method = null, $data = null) {
		$api = $this->api;
		$key = $this->getApiKey();
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "$api/$url");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_POST, $method == 'POST');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"X-Auth: $key",
			"Accept: application/json",
			"Content-type: application/json"
		));
		
		$raw = curl_exec($ch);
		curl_close($ch);
		
		return $raw;
	}

	public function testUpload() {
		$file = dirname(__FILE__) . "/data/template.docx";
		$name = "Test template";
		
		$raw = $this->apiRequest("template?name=" . urlencode($name), 'POST', file_get_contents($file));
		$response = json_decode($raw);
		
		$this->assertNotNull($response);
		$this->assertFalse(isset($response->error));
		$this->assertTrue(isset($response->template_id));
	}
	
	public function testAPI() {
		$api = $this->api;
		$key = $this->getApiKey();
		$file = dirname(__FILE__) . "/data/template.docx";
		$name = "Test template";
		$target = dirname(__FILE__) . "/data/downloaded.zip";
		
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "$api/template?name=" . urlencode($name));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents($file));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"X-Auth: $key",
			"Accept: application/json",
			"Content-type: application/json"
		));

		$raw = curl_exec($ch);
		$response = json_decode($raw);
		
		$this->assertNotNull($response);
		$this->assertFalse(isset($response->error));
		$this->assertTrue(isset($response->template_id));
		
		$template_id = $response->template_id;

		$data = array(
			'template_id' => $template_id,
			'type' => 'docx',
			'data' => [
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
			]
		);

		curl_setopt($ch, CURLOPT_URL, "$api/request");
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

		$raw = curl_exec($ch);
		$response = json_decode($raw);

		$this->assertNotNull($response);
		$this->assertFalse(isset($response->error));
		$this->assertTrue(isset($response->request_id));

		$request_id = $response->request_id;

		curl_setopt($ch, CURLOPT_POSTFIELDS, null);
		curl_setopt($ch, CURLOPT_POST, FALSE);
		curl_setopt($ch, CURLOPT_URL, "$api/request/$request_id");

		while(true) {
			$raw = curl_exec($ch);
			$response = json_decode($raw, false);

			$this->assertNotNull($response);
			$this->assertFalse(isset($response->error));
			$this->assertTrue(isset($response->status));

			if ($response->status == "done") {
				break;
			}
		}

		curl_setopt($ch, CURLOPT_URL, "$api/request/$request_id/download");

		$raw = curl_exec($ch);
		file_put_contents($target, $raw);

		curl_close($ch);
		
		$zip = new \ZipArchive();
		
		$this->assertTrue($zip->open($target));
		$this->assertNotFalse($zip->statName('document0.docx'));
		$this->assertNotFalse($zip->statName('document1.docx'));
		$this->assertFalse($zip->statName('document2.docx'));
		
		$zip->close();
		
		unlink($target);
	}
}
