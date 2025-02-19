<?php

namespace EO\Http\Requests;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ApiClient
{
	private $client;
	private $apiUrl;
	private $apiKey;

	public function __construct(string $api_url, string $api_key = null)
	{
		$this->apiUrl = $api_url;
		$this->apiKey = $api_key;
		$this->client = new Client(); // Guzzle HTTP client
	}

	/**
	 * Fetches data from a specific API endpoint.
	 *
	 * @param string $end_point The endpoint to fetch data from (e.g. 'users', 'posts', etc.).
	 * @param array $params Optional query parameters for the request.
	 *
	 * @return array|null The response data from the API or null if an error occurs.
	 */
	public function fetchData(string $end_point, array $params = []): ?array
	{
		try {
			$response = $this->client->get($this->apiUrl . '/' . $end_point, [
				'query' => $params, // Adds query parameters to the URL
				'headers' => [
					'Authorization' => 'Bearer ' . $this->apiKey, // Optional API key
					'Accept' => 'application/json',
				],
			]);

			// Decode the JSON response into an associative array
			return json_decode($response->getBody(), true);

		} catch (RequestException $e) {
			// Log error or handle it as needed
			error_log('API request failed: ' . $e->getMessage());
			return null; // Return null if the request fails
		}
	}

	/**
	 * Fetches a specific item by its ID.
	 *
	 * @param string $end_point The endpoint to fetch data from (e.g. 'users', 'posts', etc.).
	 * @param int $id The ID of the item to fetch.
	 *
	 * @return array|null The data of the specific item, or null if an error occurs.
	 */
	public function fetchById(string $end_point, int $id): ?array
	{
		return $this->fetchData($end_point . '/' . $id);
	}

	/**
	 * Fetches all items from the given endpoint.
	 *
	 * @param string $end_point The endpoint to fetch all data from.
	 *
	 * @return array|null The list of items, or null if an error occurs.
	 */
	public function fetchAll(string $end_point): ?array
	{
		return $this->fetchData($end_point);
	}

	public function createData(string $end_point, array $data): ?array
	{
		try {
			$response = $this->client->post($this->apiUrl . '/' . $end_point, [
				'headers' => [
					'Authorization' => 'Bearer ' . $this->apiKey,
					'Content-Type' => 'application/json',
					'Accept' => 'application/json',
				],
				'json' => $data,
			]);

			return json_decode($response->getBody(), true);

		} catch (RequestException $e) {
			error_log('API POST request failed: ' . $e->getMessage());
			return null;
		}
	}
}