<?php
namespace ResourcesModule;

use Drahak\Rest\Application\ResourcePresenter;
use Drahak\Rest\IResource;

/**
 * BasePresenter
 * @package ResourcesModule
 * @author Drahomír Hanák
 */
abstract class BasePresenter extends ResourcePresenter
{

    /** @var string */
    protected $defaultMimeType = IResource::JSON;

}