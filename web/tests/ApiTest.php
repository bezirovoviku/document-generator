<?php

/**
 * Tests used for determining correct functionality of API
 */
class ApiTest extends TestCase
{
	/// @var string $key cached api key
	protected $key;

	/**
	 * Create test user and returns his API key
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

	public function call($method, $uri, array $parameters = [], array $cookies = [], array $files = [], array $server = [], $content = null)
	{
		$server = array_merge($server, $this->transformHeadersToServerVars([
			'X-Auth' => $this->getApiKey(),
			'Accept' => 'application/json',
		]));
		$uri = '/api/v1/' . $uri;

		$response = parent::call($method, $uri, $parameters, $cookies, $files, $server, $content);

		$content = $response->getContent();
		$json = json_decode($content);
		if (json_last_error() === JSON_ERROR_NONE) {
			return $json;
		}
		return $response;
	}

	public function simpleCall($method, $uri, array $parameters = [], array $headers = [], $content = null)
	{
		$server = $this->transformHeadersToServerVars($headers);
		return $this->call($method, $uri, $parameters, [], [], $server, $content);
	}

	/**
	 * Tests complete process of API usage
	 */
	public function testAPI() {
		// upload
		$params = [
			'name' => 'Test template',
			'type' => 'docx'
		];
		$headers = [
			'Content-Length' => Storage::size('template.docx')
		];
		$response = $this->simpleCall('POST', 'template', $params, $headers, Storage::get('template.docx'));
		$this->assertFalse(isset($response->error), 'API returned an error');
		$this->assertTrue(isset($response->template_id), 'Template ID not presented');

		$template_id = $response->template_id;

		// create request and save its ID
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
		$response = $this->simpleCall('POST', 'request', $data);
		$this->assertFalse(isset($response->error), 'API returned an error');
		$this->assertTrue(isset($response->request_id), 'Request ID not presented');
		$request_id = $response->request_id;

		// wait for request to finish
		do {
			$response = $this->simpleCall('GET', "request/$request_id");
			$this->assertFalse(isset($response->error), 'API returned an error');
			$this->assertTrue(isset($response->status), 'Status not presented');
		} while ($response->status != 'done');

		// download resulting file and save it to tempfile
		$response = $this->simpleCall('GET', "request/$request_id/download");
		// $this->dump();
		$this->assertFalse(isset($response->error), 'API returned an error');
		$this->assertNotEmpty($response->content(), 'File contents not returned.');

		$temp = tempnam(env_path('tmp'), 'archive');
		file_put_contents($temp, $response->content());

		// check for correct archive contents
		$zip = new \ZipArchive();
		$this->assertTrue($zip->open($temp));
		$this->assertNotFalse($zip->statName('document0.docx'));
		$this->assertNotFalse($zip->statName('document1.docx'));
		$this->assertFalse($zip->statName('document2.docx'));

		$zip->close();
		unlink($temp);
	}
}
