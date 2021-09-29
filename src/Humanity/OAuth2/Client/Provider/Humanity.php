<?php

namespace Humanity\OAuth2\Client\Provider;

use \League\OAuth2\Client\Provider\AbstractProvider;
use \League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;

class Humanity extends AbstractProvider {

	/**
	 * Humanity accounts uri.
	 * @var string
	 */
	public $oauthBaseUri = 'https://accounts.humanity.com';
		
	public $companyId;
	
	/**
	 * @param array $options
	 */
	function __construct(array $options = []) {
		parent::__construct(array_merge($options, [
			'scopeSeparator' => ' '
		]));
	}

	/**
	 * Get state. If state is not set we will create it.
	 *
	 * @return string
	 */
	public function getState() {
		if (null === $this->state) {
			$this->state = md5(uniqid(rand(), true));
		}

		return $this->state;
	}

	/**
	 * Redirect user to authorization url.
	 *
	 * @param bool|true $exit
	 */
	public function redirect($exit = true) {
		header('Location: ' . $this->getAuthorizationUrl());

		if ($exit) {
			exit;
		}
	}

	public function getAuthorizationUrl($options = []) {
		return parent::getAuthorizationUrl([
			'state' => $this->getState()
		]) . '&company_id=' . $this->companyId;
	}

	public function urlAuthorize() {
		return $this->oauthBaseUri . '/oauth2/authorize';
	}

	public function urlAccessToken() {
		return $this->oauthBaseUri . '/oauth2/token';
	}

	public function urlUserDetails(AccessToken $token) {
		throw new \Exception(sprintf('Method % not implemented', __METHOD__));
	}

	public function userDetails($response, AccessToken $token) {
		throw new \Exception(sprintf('Method % not implemented', __METHOD__));
	}

    public function getBaseAuthorizationUrl()
    {
        // TODO: Implement getBaseAuthorizationUrl() method.
    }

    public function getBaseAccessTokenUrl(array $params)
    {
        // TODO: Implement getBaseAccessTokenUrl() method.
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        // TODO: Implement getResourceOwnerDetailsUrl() method.
    }

    protected function getDefaultScopes()
    {
        // TODO: Implement getDefaultScopes() method.
    }

    protected function checkResponse(ResponseInterface $response, $data)
    {
        // TODO: Implement checkResponse() method.
    }

    protected function createResourceOwner(array $response, AccessToken $token)
    {
        // TODO: Implement createResourceOwner() method.
    }
}
