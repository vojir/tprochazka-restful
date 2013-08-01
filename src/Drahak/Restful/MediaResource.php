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

	/** @var array */
	protected static $supportedTypes = array(
		self::DATA_URL
	);

	/**
	 * @param Media $media
	 * @param string $contentType
	 *
	 * @throws InvalidArgumentException when given content type is illegal for this resource
	 */
	public function __construct(Media $media, $contentType = self::DATA_URL)
	{
		$this->media = $media;
		$this->setContentType($contentType);
	}

	/**
	 * Get resource content type
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
	 *
	 * @throws InvalidArgumentException
	 */
	public function setContentType($contentType)
	{
		if (!in_array($contentType, self::$supportedTypes)) {
			throw new InvalidArgumentException(
				'Media resource supports ' . implode(', ', self::$supportedTypes) . ', "' . $contentType . '" given'
			);
		}

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
