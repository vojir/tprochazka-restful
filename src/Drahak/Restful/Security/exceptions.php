<?php
namespace Drahak\Restful\Security;

use Drahak\Restful\RuntimeException;

/**
 * SecurityException is thrown when error in security appears
 * @package Drahak\Restful\Security
 * @author Drahomír Hanák
 */
class SecurityException extends RuntimeException
{
}

/**
 * UnauthorizedRequestException
 * @package Drahak\Restful\Security
 * @author Drahomír Hanák
 */
class UnauthorizedRequestException extends SecurityException
{
}

/**
 * RequestTimeoutException is thrown when request time is not valid
 * @package Drahak\Restful\Security
 * @author Drahomír Hanák
 */
class RequestTimeoutException extends SecurityException
{
}