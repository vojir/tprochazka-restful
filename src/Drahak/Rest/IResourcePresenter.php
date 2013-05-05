<?php
namespace Drahak\Api;

use Nette\Application\IPresenter;

/**
 * REST API ResourcePresenter
 * @package Drahak\Api
 * @author Drahomír Hanák
 */
interface IResourcePresenter extends IPresenter
{

    /**
     * Set API resource
     * @return void
     */
    public function sendResource();

}