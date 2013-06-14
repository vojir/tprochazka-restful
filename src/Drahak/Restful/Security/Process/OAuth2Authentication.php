<?php
namespace Drahak\Restful\Security\Process;

use Drahak\OAuth2;
use Drahak\OAuth2\Storage\AccessTokens\IAccessTokenStorage;
use Drahak\OAuth2\Token\InvalidAccessTokenException;
use Drahak\Restful\IInput;
use Drahak\Restful\Security\AuthenticationException;

/**
 * OAuth2Authentication
 * @package Drahak\Restful\Security\Process
 * @author Drahomír Hanák
 */
class OAuth2Authentication extends AuthenticationProcess
{

	/** @var IAccessTokenStorage */
	private $storage;

	/** @var OAuth2\Http\IInput */
	private $oauthInut;

	public function __construct(IAccessTokenStorage $storage, OAuth2\Http\IInput $oauthInut)
	{
		$this->storage = $storage;
		$this->oauthInut = $oauthInut;
	}

	/**
	 * Authenticate request data
	 * @param IInput $input
	 * @return bool|void
	 *
	 * @throws AuthenticationException
	 */
	protected function authRequestData(IInput $input)
	{
		$token = $this->oauthInut->getAuthorization();
		if (!$token) {
			throw new AuthenticationException('Token was not found.');
		}

		try {
			$this->storage->getValidAccessToken($token);
		} catch (InvalidAccessTokenException $e) {
			throw new AuthenticationException('Invalid (or expired) access token.', 0, $e);
		}
	}

	/**
	 * Authenticate request time
	 * @param IInput $input
	 * @return bool
	 */
	protected function authRequestTime(IInput $input)
	{
	}


}