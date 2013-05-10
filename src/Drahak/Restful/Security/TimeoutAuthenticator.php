<?php
namespace Drahak\Restful\Security;

use Drahak\Restful\IInput;

/**
 * TimeoutAuthenticator
 * @package Drahak\Restful\Security
 * @author Drahomír Hanák
 */
class TimeoutAuthenticator extends RequestAuthenticator
{

	/**
	 * Authenticate request
	 * @param IInput $input
	 * @return string|void
	 *
	 * @throws AuthenticationException
	 * @throws RequestTimeoutException
	 */
	public function authenticate(IInput $input)
	{
		parent::authenticate($input);

		$key = $this->securityConfig['requestTimeKey'];
		$timestamp = now();
		$data = $input->getData();
		if (!isset($data[$key]) || !$data[$key]) {
			throw new RequestTimeoutException('Request time not found in requested data.');
		}

		$diff = $timestamp - $data[$key];
		if ($diff > $this->securityConfig['requestTimeout']) {
			throw new RequestTimeoutException;
		}
		return TRUE;
	}

}