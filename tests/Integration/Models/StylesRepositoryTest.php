<?php
/**
 * Created by IntelliJ IDEA.
 * User: jose
 * Date: 27/08/18
 * Time: 14:54
 */

namespace SlimApi\Tests\Integration\Models;

use MongoDB\Collection;
use MongoDB\Driver\Manager;
use PHPUnit\Framework\TestCase;
use Slim\Container;
use SlimApi\Config\Config;
use SlimApi\DependencyInjection\ApiProvider;
use SlimApi\Models\StylesRepository;

/**
 * Class StylesRepositoryTest
 *
 * @package SlimApi\Tests\Functional\Models
 */
class StylesRepositoryTest extends TestCase
{
    /**
     * @var StylesRepository
     */
    protected $repository;

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * SetUp.
     */
    public function setUp()
    {
        $container = new Container((['config' => new Config('test', __DIR__ . '/../../../config')]));
        $container->register(new ApiProvider());

        $this->repository = $container['styles_repository'];

        $this->collection = new Collection(
            new Manager($container['config']->get('db.dsn')),
            $container['config']->get('db.dbName'),
            $container['config']->get('db.stylesRepository')
        );

        $documents = json_decode(file_get_contents(__DIR__ . '/../../fixtures/styles.json'), true);
        $this->collection->insertMany($documents);
    }

    /**
     * TearDown
     */
    public function tearDown()
    {
        $this->collection->drop();
    }

    /**
     * @group Models
     */
    public function testWorks()
    {

    }
}
