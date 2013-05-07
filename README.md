Simple Nette REST API
=====================
This repository is under development.

Do not use it on production. It is only for study purposes.

Sample usage
------------

Create `BasePresenter`:

```php
<?php
namespace ResourcesModule;

use Drahak\Restful\Application\ResourcePresenter;
use Drahak\Restful\IResource;

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
```

Then create your API resource presenter:

```php
<?php
namespace ResourcesModule;

use Drahak\Restful\IResource;

/**
 * SamplePresenter resource
 * @package ResourcesModule
 * @author Drahomír Hanák
 */
class SamplePresenter extends BasePresenter
{

   protected $typeMap = array(
       'json' => IResource::JSON,
       'xml' => IResource::XML
   );

   /**
    * @GET sample[.<type xml|json>]
    */
   public function actionContent($type = 'json')
   {
       $this->resource->title = 'REST API';
       $this->resource->subtitle = '';
       $this->sendResource($this->typeMap[$type]);
   }

   /**
    * @GET sample/detail
    */
   public function actionDetail()
   {
       $this->resource->message = 'Hello world';
   }

}
```

See `@GET` annotation. There are also allowed annotations `@POST`, `@PUT`, `@HEAD`, `@DELETE`. This allows Drahak\Restful library to generate API routes for you so you don't need to do it manualy. But it's not neccessary! You can define your routes using `IResourceRoute` or its default implementation such as:

```php
<?php
use Drahak\Restful\Application\Routes\ResourceRoute;

$anyRouteList[] = new ResourceRoute('sample[.<type xml|json>]', 'Resources:Sample:content', ResourceRoute::GET);
```

There is only one more parameter unlike the Nette default Route, the request method. This allows you to generate same URL for e.g. GET and POST method. You can pass this parameter to route as a flag so you can combine more request methods such as `ResourceRoute::GET | ResourceRoute::POST` to listen on GET and POST request method in the same route.

You can also define action names dictionary for each reqest method:

```php
<?php
new ResourceRoute('myResourceName', array(
    'presenter' => 'MyResourcePresenter',
    'action' => array(
        ResourceRoute::GET => 'content',
        ResourceRoute::DELETE => 'delete'
    )
), ResourceRoute::GET | ResourceRoute::DELETE);
```

Simple CRUD resources
---------------------
Well it's nice but in many cases I define only CRUD operations so how can I do it more intuitively? Use `CrudRoute`! This childe of `ResourceRoute` predefines base CRUD operations for you. Namely, it is `Presenter:create` for PUT method, `Presenter:read` for GET, `Presenter:update` for POST and `Presenter:delete` for DELETE. Then your router will look like this:

```php
<?php
new CrudRoute('<module>/crud', 'MyResourcePresenter');
```
Note the second parameter, metadata. You can define only Presenter not action name. This is because the action name will be replaced by value from actionDictionary (`[CrudRoute::PUT => 'create', CrudRoute::GET => 'read', CrudRoute::POST => 'update', CrudRoute::DELETE => 'delete']`) which is property of `ResourceRoute` so even of `CrudRoute` since it is its child. Also note that we don't have to set flags. Default flags are setted to `CrudRoute::RESTFUL` so the route will match all request methods.

Then you can simple define your CRUD resource presenter:

```php
<?php
namespace ResourcesModule;

/**
 * CRUD resource presenter
 * @package ResourcesModule
 * @author Drahomír Hanák
 */
class CrudPresenter extends BasePresenter
{

    public function actionCreate()
    {
        $this->resource->action = 'Create';
    }

    public function actionRead()
    {
        $this->resource->action = 'Read';
    }

    public function actionUpdate()
    {
        $this->resource->action = 'Update';
    }

    public function actionDelete()
    {
        $this->resource->action = 'Delete';
    }

}
```

So that's it. Enjoy and hope you like it!