<?php
namespace Drahak\Rest\Application\Responses;

use Drahak\Rest\Mapping\IMapper;
use Drahak\Rest\Mapping\XmlMapper;
use Nette\Application\IResponse;
use Nette\Object;
use Nette\Http;

/**
 * XmlResponse
 * @package Drahak\Rest\Responses
 * @author Drahomír Hanák
 */
class XmlResponse extends Object implements IResponse
{

    /** @var IMapper */
    private $mapper;

    /**
     * @param array|\stdClass|\Traversable $data
     * @param string $rootElement
     */
    public function __construct($data, $rootElement = 'root')
    {
        $this->mapper = new XmlMapper($data, $rootElement);
    }

    /**
     * Sends response to output
     * @param Http\IRequest $httpRequest
     * @param Http\IResponse $httpResponse
     */
    public function send(Http\IRequest $httpRequest, Http\IResponse $httpResponse)
    {
        $httpResponse->setContentType('application/xml');
        echo $this->mapper->convert();
    }


}