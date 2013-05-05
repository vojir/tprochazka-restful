<?php
namespace Drahak\Rest\Mapping;

use Drahak\Rest\IMapper;
use Drahak\Rest\InvalidArgumentException;
use Nette\Object;

/**
 * XmlMapper
 * @package Drahak\Rest\Mapping
 * @author Drahomír Hanák
 */
class XmlMapper extends Object implements IMapper
{

    /** @var array|\Traversable  */
    private $data;

    /** @var \DOMDocument */
    private $xml;

    /**
     * @param array|\Traversable $data
     * @param string|null $rootElement
     *
     * @throws InvalidArgumentException
     */
    public function __construct($data, $rootElement = NULL)
    {
        if (!is_array($data) && !($data instanceof \Traversable)) {
            throw new InvalidArgumentException('Data must be of type array or Traversable');
        }
        $this->data = $rootElement ? array($rootElement => $data) : $data;

        $this->xml = new \DOMDocument('1.0', 'UTF-8');
        $this->xml->formatOutput = TRUE;
    }

    /**
     * Converts data to XML
     * @return string
     */
    public function convert()
    {
        $this->toXml($this->data);
        return $this->xml->saveXML();
    }

    /**
     * Magic to string function
     * @return string
     */
    public function __toString()
    {
        return $this->convert();
    }

    /**
     * @param $data
     * @param null $domElement
     */
    private function toXml($data, $domElement = NULL)
    {
        $domElement = is_null($domElement) ? $this->xml : $domElement;

        if (is_array($data) || $data instanceof \Traversable) {
            foreach ($data as $index => $mixedElement) {
                if (is_int($index)) {
                    if ($index == 0) {
                        $node = $domElement;
                    } else {
                        $node = $this->xml->createElement($domElement->tagName);
                        $domElement->parentNode->appendChild($node);
                    }
                } else {
                    $node = $this->xml->createElement($index);
                    $domElement->appendChild($node);
                }
                $this->toXml($mixedElement, $node);
            }
        } else {
            $domElement->appendChild($this->xml->createTextNode($data));
        }
    }

}