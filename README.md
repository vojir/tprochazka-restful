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

use Drahak\Api\Application\ResourcePresenter;
use Drahak\Api\IResource;

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

use Drahak\Api\IResource;

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

See `@GET` annotation. There are also allowed annotations `@POST`, `@PUT`, `@HEAD`, `@DELETE`. This allows Drahak\Api library to generate API routes for you so you don't need to do it manualy. But it's not neccessary! You can define your routes using `IResourceRoute` or its default implementation such as:

```php
<?php
use Drahak\Api\Application\Routes\ResourceRoute;

$anyRouteList[] = new ResourceRoute('sample[.<type xml|json>]', 'Resources:Sample:content', ResourceRoute::GET);
```

There is only one more parameter unlike the Nette default Route, the request method. This allows you to generate same URL for e.g. GET and POST method. You can pass this parameter to route as a flag so you can combine more request methods such as `ResourceRoute::GET | ResourceRoute::POST` to listen on GET and POST request method in the same route.