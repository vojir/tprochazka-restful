<?php
namespace Drahak\Rest;

use Nette\Application\IPresenter;

/**
 * REST API ResourcePresenter
 * @package Drahak\Rest
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