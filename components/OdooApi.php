<?php
namespace app\components;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class OdooApi
{
	private $url;
	private $username;
	private $password;

	public function __construct()
	{
		$this->url = "http://localhost:8069"; // Replace with actual Odoo instance URL
		$this->username = "admin"; // Admin login
		$this->password = "Abuhager1@"; // Admin password
	}

	/**
	 * Authenticate to the Odoo instance using the provided database name.
	 *
	 * @param string $dbName  The name of the database to authenticate to.
	 * @return mixed         The user ID on successful authentication, false otherwise.
	 *
	 * @throws GuzzleException
	 */
	private function authenticate($dbName)
	{
		$client = new Client();
		$response = $client->post("{$this->url}/jsonrpc", [
			'json' => [
				'jsonrpc' => '2.0',
				'method' => 'call',
				'params' => [
					'service' => 'common',
					'method' => 'authenticate',
					'args' => [
						$dbName,
						$this->username,
						$this->password,
						[]  // Context (empty dictionary)
					]
				],
				'id' => 1
			]
		]);

		if ($response->getStatusCode() == 200) {
			$body = json_decode($response->getBody()->getContents(), true);
			if (isset($body['result'])) {
				return $body['result']; // Return user ID on successful authentication
			}
		}
		return false; // Authentication failed
	}

	/**
	 * Clone a database from a source database to a new database.
	 *
	 * @param string $sourceDb   The name of the source database to clone.
	 * @param string $newDb      The name of the new database to create.
	 * @return string|false      The URL of the cloned database, or false if cloning failed.
	 *
	 * @throws GuzzleException
	 */
	public function cloneDatabase($sourceDb, $newDb)
	{
		$client = new Client();
		$response = $client->post("{$this->url}/jsonrpc", [
			'json' => [
				'jsonrpc' => '2.0',
				'method' => 'call',
				'params' => [
					'service' => 'db',
					'method' => 'duplicate_database',
					'args' => [
						$this->password,  // Admin password
						$sourceDb,        // Original database to copy
						$newDb            // New database name
					]
				],
				'id' => 1
			]
		]);

		if ($response->getStatusCode() == 200) {
			$body = json_decode($response->getBody()->getContents(), true);
			if (isset($body['result'])) {
				return "{$this->url}/web?db=$newDb"; // Return the URL of the cloned database
			}
		}
		return false; // Cloning failed
	}

	/**
	 * Create a new user in the specified database.
	 *
	 * @param string $dbName      The database where the user will be created.
	 * @param string $email       The email address for the new user.
	 * @param string $password    The password for the new user.
	 * @return bool               True if the user was created successfully, false otherwise.
	 *
	 * @throws GuzzleException
	 */
	public function createUser($dbName, $email, $password)
	{

		$uid = $this->authenticate($dbName);

		if ($uid) {
			$client = new Client();
			$response = $client->post("{$this->url}/jsonrpc", [
				'json' => [
					'jsonrpc' => '2.0',
					'method' => 'call',
					'params' => [
						'service' => 'object',
						'method' => 'execute_kw',
						'args' => [
							$dbName,              // The database where the user will be created
							$uid,                 // Authenticated user ID
							$this->password,      // Admin password
							'res.users',          // Model name (users)
							'create',             // Action (create)
							[[
								'name' => 'Khaled',  // Name of the user
								'login' => $email,   // Login (email)
								'password' => $password,  // User password
								'email' => $email,   // Optional email
								'groups_id' => [[6, 0, [1]]] // Assign to group (e.g., Administrator)
							]]
						]
					],
					'id' => 2
				]
			]);

			if ($response->getStatusCode() == 200) {
				$body = json_decode($response->getBody()->getContents(), true);
				if (isset($body['result'])) {
					return true; // Successfully created the user
				}
			}
		}
		return false; // Failed to create the user
	}

	/**
	 * Update a company record in Odoo using the data from the form.
	 *
	 * @param string $dbName       The name of the new database.
	 * @param int $companyId       The company record ID to update.
	 * @param array $companyData   An associative array with company information.
	 * @return mixed               True if update was successful, false otherwise.
	 *
	 * @throws GuzzleException
	 */
	public function updateCompany($dbName, $companyId, $companyData)
	{
		// Authenticate to get the user ID.
		$uid = $this->authenticate($dbName);

		if ($uid) {
			$client = new Client();
			$response = $client->post("{$this->url}/jsonrpc", [
				'json' => [
					'jsonrpc' => '2.0',
					'method'  => 'call',
					'params'  => [
						'service' => 'object',
						'method'  => 'execute_kw',
						'args'    => [
							$dbName,           // The new database name
							$uid,              // Authenticated user ID
							$this->password,   // Admin password
							'res.company',     // Model name (company)
							'write',           // Action: update existing record
							[[$companyId], $companyData]  // [record IDs, data array]
						]
					],
					'id' => 4
				]
			]);

			if ($response->getStatusCode() == 200) {
				$body = json_decode($response->getBody()->getContents(), true);
				if (isset($body['result']) && $body['result'] === true) {
					return true; // Update was successful
				}
			}
		}
		return false; // Update failed
	}

	/**
	 * Get the country ID from the database based on the country name.
	 *
	 * @param string $dbName      The database name to search in.
	 * @param string $countryName The name of the country to find.
	 * @return int|null           The ID of the country, or null if not found.
	 *
	 * @throws GuzzleException
	 */
	public function getCountryId($dbName, $countryName)
	{
		$uid = $this->authenticate($dbName);
		if ($uid) {
			$client = new \GuzzleHttp\Client();
			$response = $client->post("{$this->url}/jsonrpc", [
				'json' => [
					'jsonrpc' => '2.0',
					'method'  => 'call',
					'params'  => [
						'service' => 'object',
						'method'  => 'execute_kw',
						'args'    => [
							$dbName,
							$uid,
							$this->password,
							'res.country',
							'search_read',
							[[['name', '=', $countryName]]],
							['fields' => ['id']]
						]
					],
					'id' => 5
				]
			]);

			$body = json_decode($response->getBody()->getContents(), true);
			return isset($body['result'][0]['id']) ? $body['result'][0]['id'] : null;
		}
		return null;
	}

}
