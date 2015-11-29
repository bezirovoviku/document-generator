<?php
namespace Docgen;

class Api {
	///@var string $url API url
	protected $url;
	///@var string $key API key
	protected $key;

	/**
	 * @param string $url url to API
	 * @param string $key your API key
	 */
	public function __construct($url = null, $key = null) {
		$this->url = $url;
		$this->key = $key;
	}

	/**
	 * Sets API url
	 *
	 * @param string $url API url
	 */
	public function setUrl($url) {
		$this->url = $url;
	}

	/**
	 * Sets your API key
	 *
	 * @param string $key API key
	 */
	public function setKey($key) {
		$this->key = $key;
	}

	/**
	 * Uploads new template
	 *
	 * @param string $name template name
	 * @param string $file path to template file (docx document)
	 * @return int uploaded template ID
	 */
	public function uploadTemplate($name, $file) {
		if (!file_exists($file)) {
			throw new \Exception("File '$file' doesn't exists.");
		}

		return $this->apiJSONRequest("template?name=" . urlencode($name), "POST", file_get_contents($file))->template_id;
	}
	
	/**
	 * Deletes existing template
	 *
	 * @param int $template_id
	 * @return string
	 */
	public function deleteTemplate($template_id) {
		return $this->apiRequest("template/$template_id", "DELETE");
	}

	/**
	 * Adds new document batch to queue
	 *
	 * @param int          $template_id ID of existing template
	 * @param array|string $data        Documents data in specified format
	 * @param string       $data_type   Data format, json, csv or xml. Default json
	 * @return int request id
	 */
	public function sendRequest($template_id, $data, $data_type = 'json') {
		$request = array(
			'template_id' => $template_id,
			'data_type' => $data_type,
			'data' => $data,
			'type' => 'docx'
		);

		$raw = json_encode($request);
		if (!$raw) {
			throw new \Exception("Failed to convert data to JSON.");
		}

		return $this->apiJSONRequest("request", "POST", $raw)->request_id;
	}

	/**
	 * Fetches informations about request
	 * 
	 * @param int $request_id ID of request
	 * @return object request data
	 */
	public function getRequestInfo($request_id) {
		return $this->apiJSONRequest("request/$request_id");
	}

	/**
	 * Downloads completed request
	 *
	 * @param int    $request_id ID of request
	 * @param string $file       path where downloaded archive should be placed
	 */
	public function downloadRequest($request_id, $file) {
		if (!file_put_contents($file, $this->apiRequest("request/$request_id/download"))) {
			throw new \Exception("Failed to save downloaded archive.");
		}
	}

	/**
	 * Makes request to the API and converts response to object
	 *
	 * @param string $url    url relative to API url
	 * @param string $method POST or GET, default GET
	 * @param string $data   data to be put into request body
	 * @return object response
	 */
	protected function apiJSONRequest($url, $method = null, $data = null) {
		$raw = $this->apiRequest($url, $method, $data);
		$json = json_decode($raw);
		if ($json == null) {
			throw new \Exception("Expected json, got: $raw");
		}
		if (isset($json->error)) {
			throw new \Exception("API Error: $json->error");
		}
		
		return $json;
	}

	/*
	 * Makes request to API and returns result
	 *
	 * @param string $url    url relative to API url
	 * @param string $method POST or GET, default GET
	 * @param string $data   data to be put into request body
	 * @return string raw response data
	 */
	protected function apiRequest($url, $method = null, $data = null) {
		$api = $this->url;
		$key = $this->key;
		
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
}