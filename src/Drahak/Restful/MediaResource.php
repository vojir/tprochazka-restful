<?php
namespace Drahak\Restful;

use Nette\Object;

/**
 * MediaResource
 * @package Drahak\Restful
 * @author Drahomír Hanák
 *
 * @property string $contentType
 * @property Media $media
 */
class MediaResource extends Object implements IResource
{

	/** @var string */
	private $contentType;

	/** @var Media */
	private $media;

	/**
	 * @param Media $media
	 * @param string $contentType
	 */
	public function __construct(Media $media, $contentType = self::DATA_URL)
	{
		$this->media = $media;
		$this->contentType = $contentType;
	}

	/**
	 * Get content type
	 * @return string
	 */
	public function getContentType()
	{
		return $this->contentType;
	}

	/**
	 * Set content type
	 * @param string $contentType
	 * @return MediaResource
	 */
	public function setContentType($contentType)
	{
		$this->contentType = $contentType;
		return $this;
	}

	/**
	 * Get result set data
	 * @return array|\stdClass|\Traversable
	 */
	public function getData()
	{
		return $this->media;
	}

}
