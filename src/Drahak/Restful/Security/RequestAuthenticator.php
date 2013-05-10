<?php
namespace Drahak\Restful\Security;

use Drahak\Restful\IInput;
use Drahak\Restful\IAuthTokenCalculator;
use Drahak\Restful\IRequestAuthenticator;
use Drahak\Restful\InvalidArgumentException;
use Nette\Http\IRequest;
use Nette\Object;

/**
 * RequestAuthenticator
 * @package Drahak\Restful\Security
 * @author Drahomír Hanák
 */
class RequestAuthenticator extends Object implements IRequestAuthenticator
{

	/** Auth token header */
	const AUTH_HEADER = 'X-HTTP-AUTH-TOKEN';

	/** @var array */
	protected $securityConfig;

	/** @var IRequest */
	private $request;

	/**
	 * @param array $securityConfig
	 * @param IRequest $request
	 * @param IAuthTokenCalculator $calculator
	 *
	 * @throws \Drahak\Restful\InvalidArgumentException
	 */
	public function __construct(array $securityConfig, IRequest $request, IAuthTokenCalculator $calculator)
	{
		if (!isset($this->securityConfig['privateKey'])) {
			throw new InvalidArgumentException('Private key not found in security config');
		}

		$this->request = $request;
		$this->calculator = $calculator;
		$this->securityConfig = $securityConfig;
		$this->calculator->setPrivateKey($this->securityConfig['privateKey']);
	}

	/**
	 * Authenticate request
	 * @param IInput $input
	 * @return string
	 *
	 * @throws AuthenticationException
	 */
	public function authenticate(IInput $input)
	{
		$requested = $this->getRequestedHash();
		$expected = $this->getExpectedHash($input);
		if (!$requested) {
			throw new AuthenticationException('Authorization header not found.');
		}

		if ($requested !== $expected) {
			throw new AuthenticationException('Authentication codes do not match.');
		}
		return $requested;
	}

	/**
	 * Get request hash
	 * @return string
	 */
	protected function getRequestedHash()
	{
		return $this->request->getHeader(self::AUTH_HEADER);
	}

	/**
	 * Get expected hash
	 * @param IInput $input
	 * @return string
	 */
	protected function getExpectedHash(IInput $input)
	{
		return $this->calculator->calculate($input);
	}

}