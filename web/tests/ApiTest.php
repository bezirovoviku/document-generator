<?php

/**
 * Tests used for determining correct functionality of API
 */
class ApiTest extends TestCase
{
	///@var string $key cached api key
	protected $key;

	/**
	 * Returns first avaible API key
	 * @return string api key
	 */
	protected function getApiKey() {
		if (!$this->key) {
			$user = factory(App\User::class)->create([
				'request_limit' => 0, // = unlimited
			]);
			$this->key = $user->api_key;
		}
		return $this->key;
	}

	/**
	 * Helper function for api requests
	 */
	protected function apiRequest($url, $method = 'GET', $data = null, $type = 'application/json') {
		$server = ['HTTP_X-Auth' => $this->getApiKey(), 'HTTP_Content-length' => strlen($data)];
		if ($type)
			$server['CONTENT_TYPE'] = $type;

		return $this->call($method, '/api/v1/' . $url, [], [], [], $server, $data);
	}

	/**
	 * Tests template upload
	 */
	public function testUpload() {
		$file = env_path('template.docx');
		$name = 'Test template';

		$response = $this->apiRequest('template?name=' . urlencode($name), 'POST', file_get_contents($file), null);
		//$response = $this->call('POST', '/api/v1/template?name=' .  urlencode($name), file_get_contents($file), [], ['HTTP_X-Auth' => $this->getApiKey()], [], file_get_contents($file));
		$json = json_decode($response->content());

		$this->assertEquals(200, $response->status());
		$this->assertNotNull($json);
		$this->assertFalse(isset($json->error));
		$this->assertTrue(isset($json->template_id));
	}

	/**
	 * Tests complete process of API usage
	 */
	public function testAPI() {
		//Prepare template values
		$file = env_path('template.docx');
		$name = 'Test template';

		//$response = $this->call('POST', '/api/v1/template?name=' .  urlencode($name), file_get_contents($file), [], ['HTTP_X-Auth' => $this->getApiKey()], [], file_get_contents($file));

		/** Upload template and save its ID **/

		$response = $this->apiRequest('template?name=' . urlencode($name), 'POST', file_get_contents($file));
		$json = json_decode($response->content());

		$this->assertEquals(200, $response->status());

		$this->assertNotNull($json);
		$this->assertFalse(isset($json->error));
		$this->assertTrue(isset($json->template_id));

		$template_id = $json->template_id;

		/** Create request and save its ID **/

		$data = array(
			'template_id' => $template_id,
			'type' => 'docx',
			'data_type' => 'json',
			'data' => [
				array(
					'nadpis' => 'Nadpis dokumentu 1',
					'items' => array(
						array('name' => 'Item 1'),
						array('name' => 'Item 2'),
						array('name' => 'Item 3'),
					),
					'date' => time(),
					'format' => 'd-m-Y H:i:s',
					'number' => 1236575471.5647
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
					),
					'date' => '2015-01-02 15:40:13',
					'format' => 'Y.m.d H:i:s',
					'number' => 879872475
				)
			]
		);

		$response = $this->apiRequest('request/', 'POST', json_encode($data));
		$json = json_decode($response->content());

		$this->assertEquals(200, $response->status());
		$this->assertNotNull($json);
		$this->assertFalse(isset($json->error));
		$this->assertTrue(isset($json->request_id));

		$request_id = $json->request_id;

		/** Wait for request to finish **/

		while(true) {
			$response = $this->apiRequest("request/$request_id", 'GET', json_encode($data));
			$json = json_decode($response->content());

			$this->assertEquals(200, $response->status());
			$this->assertNotNull($json);
			$this->assertFalse(isset($json->error));
			$this->assertTrue(isset($json->status));

			if ($json->status == 'done') {
				break;
			}
		}

		//Download resulting file and save it to result folder
		$response = $this->apiRequest("request/$request_id/download", 'GET');

		$this->assertTrue($response instanceof \Symfony\Component\HttpFoundation\BinaryFileResponse);

		$archive = $response->getFile()->getRealPath();

		//Check for correct archive contents
		$zip = new \ZipArchive();

		$this->assertTrue($zip->open($archive));
		$this->assertNotFalse($zip->statName('document0.docx'));
		$this->assertNotFalse($zip->statName('document1.docx'));
		$this->assertFalse($zip->statName('document2.docx'));

		$zip->close();
	}
}
