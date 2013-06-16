<?php
namespace Drahak\Restful\Utils;

use Drahak\Restful\InvalidStateException;
use Nette;

/**
 * Paginator
 * @package Drahak\Restful\Utils
 * @author Drahomír Hanák
 *
 * @property-write string $url
 * @property-write string $nextPageUrl
 * @property-write string $lastPageUrl
 */
class Paginator extends Nette\Utils\Paginator
{

	/** @var Nette\Http\Url */
	private $url;

	/**
	 * Set paginator resource URL target
	 * @param string $url
	 * @return Paginator
	 */
	public function setUrl($url)
	{
		$this->url = new Nette\Http\Url($url);
		return $this;
	}

	/**
	 * Get next paginator resource URL
	 * @return Nette\Http\Url
	 *
	 * @throws InvalidStateException
	 */
	public function getNextPageUrl()
	{
		if (!$this->url) {
			throw new InvalidStateException('Trying to get nest page URL but base URL not set');
		}

		$this->page++;
		return $this->url->appendQuery(array(
			'offset' => $this->getOffset(),
			'limit' => $this->getItemsPerPage()
		));
	}

	/**
	 * Get last page URL
	 * @return Nette\Http\Url|NULL
	 *
	 * @throws InvalidStateException
	 */
	public function getLastPageUrl()
	{
		if (!$this->url) {
			throw new InvalidStateException('Trying to get last page URL but base URL not set');
		}

		if (!$this->getLastPage()) {
			return NULL;
		}

		return $this->url->appendQuery(array(
			'offset' => $this->getLastPage() * $this->getItemsPerPage() - $this->getItemsPerPage(),
			'limit' => $this->getItemsPerPage()
		));
	}

}