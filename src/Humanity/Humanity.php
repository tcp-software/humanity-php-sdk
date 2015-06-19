<?php

namespace Humanity;

use Guzzle\Http\Client;
use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Http\Message\RequestInterface;
use Humanity\Entity\Employee as EmployeeEntity;
use Humanity\OAuth2\Client\Provider\Humanity as Provider;
use Humanity\Repository\Company as CompanyRepository;
use Humanity\Repository\Employee as EmployeeRepository;
use Humanity\Repository\Location as LocationRepository;
use Humanity\Repository\Position as PositionRepository;
use Humanity\Repository\Shift as ShiftRepository;
use Humanity\Repository\Timeclock as TimeclockRepository;
use Humanity\Storage\Adapter\AdapterInterface;
use Humanity\Storage\Adapter\Session as Storage;
use League\OAuth2\Client\Exception\IDPException;
use League\OAuth2\Client\Token\AccessToken;

/**
 * @author Dusan Vejin <dutekvejin@gmail.com>
 */
class Humanity {

	/**
	 * Humanity api uri.
	 * @var string
	 */
	protected $apiBaseUri = 'https://publicapi.humanity.com/v1';

	/**
	 * @var Provider
	 */
	protected $provider;

	/**
	 * @var Storage
	 */
	protected $storage;

	/**
	 * @var AccessToken
	 */
	protected $accessToken;

	/**
	 * @var Client
	 */
	protected $client;

	/**
	 * @var array
	 */
	protected $repositories = [];

	/**
	 * @param array $options
	 */
	public function __construct(array $options = []) {
		if (isset($options['provider'])) {
			if ($options['provider'] instanceof Provider) {
				$this->setProvider($options['provider']);
			} elseif (is_array($options['provider'])) {
				$this->setProvider(new Provider($options['provider']));
			}
		}

		if (isset($options['tokenStorage']) && $options['tokenStorage'] instanceof AdapterInterface) {
			$this->setStorage($options['tokenStorage']);
		} else {
			$this->setStorage(new Storage());
		}

		$this->client = new Client($this->getApiBaseUri());
	}

	/**
	 * @return string
	 */
	public function getApiBaseUri() {
		return $this->apiBaseUri;
	}

	/**
	 * @param string $apiBaseUri
	 */
	public function setApiBaseUri($apiBaseUri) {
		$this->apiBaseUri = $apiBaseUri;
	}

	/**
	 * @return Provider
	 */
	public function getProvider() {
		return $this->provider;
	}

	/**
	 * @param Provider $provider
	 */
	public function setProvider(Provider $provider) {
		$this->provider = $provider;
	}

	/**
	 * @return Storage
	 */
	public function getStorage() {
		return $this->storage;
	}

	/**
	 * @param Storage $storage
	 */
	public function setStorage(Storage $storage) {
		$this->storage = $storage;
	}

	/**
	 * @return AccessToken
	 */
	public function getAccessToken() {
		return $this->accessToken;
	}

	/**
	 * @param AccessToken $accessToken
	 */
	public function setAccessToken(AccessToken $accessToken) {
		$this->accessToken = $accessToken;
	}

	/**
	 * Obtain an access token.
	 *
	 * @return AccessToken
	 * @throws \Exception
	 * @throws IDPException
	 */
	public function obtainAccessToken() {
		$tokenStorage = $this->getStorage();

		if ($tokenStorage->has('humanity-access_token')) {
			$this->setAccessToken(unserialize($tokenStorage->get('humanity-access_token')));
			return $this->getAccessToken();
		}

		$provider = $this->getProvider();

		if (!$tokenStorage->has('humanity-oauth2_state')) {
			// Persist state to storage
			$tokenStorage->set('humanity-oauth2_state', $provider->getState());
			// Redirect the user
			$provider->redirect();
		}

		$code = filter_input(INPUT_GET, 'code');
		$state = filter_input(INPUT_GET, 'state');
		$persistedState = $tokenStorage->get('humanity-oauth2_state');

		if (!$code || $state != $persistedState) {
			$error = filter_input(INPUT_GET, 'error') ?: 'error' ;
			$errorDescription = filter_input(INPUT_GET, 'error_description') ?: 'An unknown error happened';

			throw new \Exception(sprintf('%s: %s', $error, $errorDescription));
		}

		$accessToken = $provider->getAccessToken('authorization_code', [
			'code' => $code
		]);

		$this->setAccessToken($accessToken);
		$tokenStorage->set('humanity-access_token', serialize($accessToken));

		return $accessToken;
	}

	/**
	 * @param string $method
	 * @param string $path
	 * @param mixed $binds
	 * @param array $options
	 *
	 * @return RequestInterface
	 */
	protected function prepare($method, $path, $binds, array $options = []) {
		if (null !== $binds) {
			if (is_scalar($binds)) {
				$binds = ['id' => $binds];
			}

			if (is_array($binds)) {
				foreach ($binds as $key => $value) {
					if (is_scalar($value)) {
						$path = str_replace(':' . $key, (string) $value, $path);
					}
				}
			}
		}

		if (!isset($options['query'])) {
			$options['query'] = [];
		}

		$options['query'] = array_merge($options['query'], [
			'access_token' => $this->getAccessToken()->accessToken,
			'suppress_response_codes' => 1,
		]);

		return $this->client->createRequest($method, $path, null, null, $options);
	}

	/**
	 * Execute GET request.
	 *
	 * @param string $path
	 * @param array  $binds
	 * @param array  $query
	 *
	 * @return Response
	 */
	public function get($path, $binds = null, array $query = []) {
		$httpRequest = $this->prepare('GET', $path, $binds, [
			'query' => $query,
		]);

		try {
			$httpResponse = $httpRequest->send();
		}  catch (BadResponseException $exc) {
			$httpResponse = $exc->getResponse();
		}

		$json = $httpResponse->json();

		$response = new Response();
		$response->setStatus($json['status']);
		$response->setError($json['error']);

		if (isset($json['data']['items'])) {
			$response->setData($json['data']['items']);
		} else {
			$response->setData($json['data']);
		}

		return $response;
	}

	/**
	 * Execute POST request.
	 *
	 * @param string $path
	 * @param array  $binds
	 * @param array  $data
	 *
	 * @return Response
	 */
	public function post($path, $binds = null, array $data = []) {
		$httpRequest = $this->prepare('POST', $path, $binds, [
			'body' => $data
		]);

		try {
			$httpResponse = $httpRequest->send();
		}  catch (BadResponseException $exc) {
			$httpResponse = $exc->getResponse();
		}

		$json = $httpResponse->json();

		$response = new Response();
		$response->setStatus($json['status']);
		$response->setError($json['error']);

		if (isset($json['data']['items'])) {
			$response->setData($json['data']['items']);
		} else {
			$response->setData($json['data']);
		}

		return $response;
	}

	/**
	 * Execute PUT request.
	 *
	 * @param string $path
	 * @param array  $binds
	 * @param array  $data
	 *
	 * @return Response
	 */
	public function put($path, $binds = null, array $data = []) {
		$httpRequest = $this->prepare('PUT', $path, $binds, [
			'body' => $data
		]);

		try {
			$httpResponse = $httpRequest->send();
		}  catch (BadResponseException $exc) {
			$httpResponse = $exc->getResponse();
		}

		$json = $httpResponse->json();

		$response = new Response();
		$response->setStatus($json['status']);
		$response->setError($json['error']);

		if (isset($json['data']['items'])) {
			$response->setData($json['data']['items']);
		} else {
			$response->setData($json['data']);
		}

		return $response;
	}

	/**
	 * Execute DELETE request.
	 *
	 * @param string $path
	 * @param array  $binds
	 *
	 * @return Response
	 */
	public function delete($path, $binds = null) {
		$httpRequest = $this->prepare('DELETE', $path, $binds);

		try {
			$httpResponse = $httpRequest->send();
		}  catch (BadResponseException $exc) {
			$httpResponse = $exc->getResponse();
		}

		$json = $httpResponse->json();

		$response = new Response();
		$response->setStatus($json['status']);
		$response->setError($json['error']);

		if (isset($json['data']['items'])) {
			$response->setData($json['data']['items']);
		} else {
			$response->setData($json['data']);
		}

		return $response;
	}

	/**
	 * @return EmployeeRepository
	 */
	public function getEmployeeRepository() {
		if (!isset($this->repositories['employee'])) {
			$this->repositories['employee'] = new EmployeeRepository($this);
		}

		return $this->repositories['employee'];
	}

	/**
	 * @return CompanyRepository
	 */
	public function getCompanyRepository() {
		if (!isset($this->repositories['company'])) {
			$this->repositories['company'] = new CompanyRepository($this);
		}

		return $this->repositories['company'];
	}

	/**
	 * @return LocationRepository
	 */
	public function getLocationRepository() {
		if (!isset($this->repositories['location'])) {
			$this->repositories['location'] = new LocationRepository($this);
		}

		return $this->repositories['location'];
	}

	/**
	 * @return PositionRepository
	 */
	public function getPositionRepository() {
		if (!isset($this->repositories['position'])) {
			$this->repositories['position'] = new PositionRepository($this);
		}

		return $this->repositories['position'];
	}

	/**
	 * @return ShiftRepository
	 */
	public function getShiftRepository() {
		if (!isset($this->repositories['shift'])) {
			$this->repositories['shift'] = new ShiftRepository($this);
		}

		return $this->repositories['shift'];
	}

	/**
	 * @return TimeclockRepository
	 */
	public function getTimeclockRepository() {
		if (!isset($this->repositories['timeclock'])) {
			$this->repositories['timeclock'] = new TimeclockRepository($this);
		}

		return $this->repositories['timeclock'];
	}

	/**
	 * Get current employee.
	 *
	 * @return EmployeeEntity|null
	 */
	public function me() {
		return $this->getEmployeeRepository()->me();
	}

}