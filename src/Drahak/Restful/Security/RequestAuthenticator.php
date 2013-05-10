<?php
namespace Drahak\Restful\Security;

use Drahak\Restful\IAuthTokenCalculator;
use Drahak\Restful\IInput;
use Nette\Http\IRequest;
use Nette\Object;

/**
 * RequestAuthenticator
 * @package Drahak\Restful\Security
 * @author Drahomír Hanák
 */
class RequestAuthenticator extends Object
{

	/** Auth token header */
	const AUTH_HEADER = 'X-HTTP-AUTH-TOKEN';

	/** @var string */
	private $privateKey;

	/** @var IRequest */
	private $request;

	public function __construct(IRequest $request, IAuthTokenCalculator $calculator, $privateKey)
	{
		$this->request = $request;
		$this->calculator = $calculator;
		$this->privateKey = $privateKey;
		$this->calculator->setPrivateKey($this->privateKey);
	}

	/**
	 * Authenticate request
	 * @param \Drahak\Restful\IInput $input
	 * @throws UnauthorizedRequestException
	 * @return bool
	 */
	public function authenticate(IInput $input)
	{
		$generatedHash = $this->calculator->calculate($input);
		$sentHash = $this->request->getHeader(self::AUTH_HEADER);
		if (!$sentHash) {
			throw new UnauthorizedRequestException('Authorization header not found.');
		}

		if ($sentHash !== $generatedHash) {
			throw new UnauthorizedRequestException('Authentication codes do not match.');
		}
		return TRUE;
	}

}