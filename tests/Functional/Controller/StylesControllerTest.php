<?php
/**
 * Created by IntelliJ IDEA.
 * User: jose
 * Date: 28/08/18
 * Time: 08:52
 */

namespace SlimApi\Tests\Functional\Controller;

use MongoDB\Collection;
use MongoDB\Driver\Manager;
use PHPUnit\Framework\TestCase;
use Pimple\Container;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\RequestBody;
use Slim\Http\Response;
use Slim\Http\Uri;
use SlimApi\Controller\StylesController;
use SlimApi\Models\RepositoryException\RepositoryException;
use SlimApi\Models\StylesRepository;
use SlimApi\Tests\ContainerTestTrait;

/**
 * Class StylesControllerTest
 * @package SlimApi\Tests\Functional\Controller
 */
class StylesControllerTest extends TestCase
{
    use ContainerTestTrait;

    /**
     * @var StylesController
     */
    protected $controller;

    /**
     * @var string Json array with all the documents
     */
    protected $documents;

    /**
     * @var Collection
     */
    protected $collection;

    public function setUp()
    {
        $container = $this->setUpContainer();

        $this->controller = $container['styles_controller'];

        $this->collection = new Collection(
            new Manager($container['config']->get('db.dsn')),
            $container['config']->get('db.dbName'),
            $container['config']->get('db.stylesRepository')
        );

        $this->documents = json_decode(file_get_contents(__DIR__ . '/../../fixtures/styles.json'), true);
        $docs = $this->documents;
        foreach ($docs as &$d) {
            if (!empty($d['tags'])) {
                $d['tags'] = explode(',', $d['tags']);
            }
        }
        $this->collection->insertMany($docs);
    }

    public function tearDown()
    {
        $this->collection->drop();
    }

    /**
     * @group Controller
     */
    public function testStylesEndPointGetBackAllTheDocuments()
    {
        $request = $this->getRequest('');

        $response = $this->controller->getStyles($request, new Response(), []);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(json_encode($this->documents), (string)$response->getBody());
    }

    /**
     * @group Controller
     */
    public function testStylesEndPointGetDocumentsUsingTagParam()
    {
        $request = $this->getRequest('{"tag":"Rainbow"}');

        $response = $this->controller->getStyles($request, new Response(), []);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(json_encode([$this->documents[1]]), (string)$response->getBody());
    }

    /**
     * @group Controller
     */
    public function testStylesEndPointGetDocumentsUsingSearchParam()
    {
        // Tags are matched before the description
        $expectedResult[] = $this->documents[3];
        $expectedResult[] = $this->documents[1];

        $request = $this->getRequest('{"search":"brooklyn"}');

        $response = $this->controller->getStyles($request, new Response(), []);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(json_encode($expectedResult), (string)$response->getBody());
    }

    /**
     * @group Controller
     */
    public function testGet500OnDBException()
    {
        $collection = $this->getMockBuilder(StylesRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAllStyles'])
            ->getMock();
        $collection->method('getAllStyles')
            ->willThrowException(new RepositoryException('fake exception'));

        $controller = new StylesController($collection);

        $request = $this->getRequest('');

        $response = $controller->getStyles($request, new Response(), []);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(500, $response->getStatusCode());
    }

    /**
     * string $body
     */
    private function getRequest($body)
    {
        $reqBody = new RequestBody();
        if (!is_null($body)) {
            $reqBody->write($body);
        }

        return (new Request(
            'GET',
            Uri::createFromString('www.fake.com'),
            new Headers(),
            [],
            [],
            $reqBody
        ))->withHeader('Content-type', 'application/json');
    }
}
