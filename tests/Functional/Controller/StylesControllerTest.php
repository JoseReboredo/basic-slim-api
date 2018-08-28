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
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Uri;
use SlimApi\Config\Config;
use SlimApi\Controller\StylesController;
use SlimApi\DependencyInjection\ApiProvider;

/**
 * Class StylesControllerTest
 * @package SlimApi\Tests\Functional\Controller
 */
class StylesControllerTest extends TestCase
{
    /**
     * @var StylesController
     */
    protected $controller;

    public function setUp()
    {
        $container = new Container((['config' => new Config('test', __DIR__ . '/../../config')]));
        $container->register(new ApiProvider());

        $this->controller = $container['styles_controller'];

        $collection = new Collection(
            new Manager($container['config']->get('db.dsn')),
            $container['config']->get('db.dbName'),
            $container['config']->get('db.stylesRepository')
        );

        $documents = json_decode(file_get_contents(__DIR__ . '/../../fixtures/styles.json'), true);
        foreach ($documents as &$d) {
            if (!empty($d['tags'])) {
                $d['tags'] = explode(',', $d['tags']);
            }
        }
        $collection->insertMany($documents);
    }

    /**
     * @group Controller
     */
    public function testStylesEndPointWorksFine()
    {
        $this->controller->getStyles(new Requet(), new Response(), []);
    }
}