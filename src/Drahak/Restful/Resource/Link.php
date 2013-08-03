<?php
namespace Drahak\Restful\Resource;

use Nette\Object;

/**
 * Link representation in resource
 * @package Drahak\Restful\Resource
 * @author Drahomír Hanák
 *
 * @property-read string $href
 * @property-read string $rel
 */
class Link extends Object implements IResourceElement
{

	/** Resource element name */
	const NAME = 'link';

	/** Link pointing on self */
	const SELF = 'self';
	/** Link pointing on next page */
	const NEXT = 'next';
	/** Link pointing on previous page */
	const PREVIOUS = 'prev';
	/** Link pointing on last page */
	const LAST = 'last';

	/** @var string */
	private $href;

	/** @var string */
	private $rel;

	/**
	 * @param string $href
	 * @param string $rel
	 */
	public function __construct($href, $rel = self::SELF)
	{
		$this->href = $href;
		$this->rel = $rel;
	}

	/**
	 * Get link URL
	 * @return string
	 */
	public function getHref()
	{
		return $this->href;
	}

	/**
	 * Get link rel
	 * @return string
	 */
	public function getRel()
	{
		return $this->rel;
	}

	/**
	 * Converts link to string
	 * @return string
	 */
	public function __toString()
	{
		return '<' . $this->href . '>;rel="' . $this->rel . '"';
	}

	/****************** Resource element interface ******************/

	/**
	 * Get resource element name
	 * @return string
	 */
	public function getName()
	{
		return self::NAME;
	}

	/**
	 * Get element value or array data
	 * @return mixed
	 */
	public function getData()
	{
		return array(
			'href' => $this->href,
			'rel' => $this->rel
		);
	}


}
