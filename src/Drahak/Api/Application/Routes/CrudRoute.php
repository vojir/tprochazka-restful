<?php
namespace Drahak\Api\Application\Routes;

use Drahak\Api\IResourceRouter;

/**
 * Resource CrudRoute to simple resource creation
 * @package Drahak\Api\Routes
 * @author Drahomír Hanák
 *
 * {@inheritdoc}
 */
class CrudRoute extends ResourceRoute
{

    /** Presenter action names */
    const ACTION_CREATE = 'create';
    const ACTION_READ = 'read';
    const ACTION_UPDATE = 'update';
    const ACTION_DELETE = 'delete';

    public function __construct($mask, $presenter = '', $flags = IResourceRouter::CRUD)
    {
        parent::__construct($mask, $presenter . ':default', $flags);
        $this->actionDictionary = array(
            IResourceRouter::PUT => self::ACTION_CREATE,
            IResourceRouter::GET => self::ACTION_READ,
            IResourceRouter::POST => self::ACTION_UPDATE,
            IResourceRouter::DELETE => self::ACTION_DELETE
        );
    }

}