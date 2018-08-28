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
     * @var array
     */
    protected $documents;

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

        $this->documents = json_decode(file_get_contents(__DIR__ . '/../../fixtures/styles.json'), true);

        $docs = $this->documents;
        foreach ($docs as &$d) {
            if (!empty($d['tags'])) {
                $d['tags'] = explode(',', $d['tags']);
            }
        }
        $this->collection->insertMany($docs);
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
    public function testGetAllStylesWorks()
    {
        $res = $this->repository->getAllStyles();

        $this->assertEquals(6, count($res));
        $this->assertEquals($this->documents, $res);
    }

    /**
     * @group Models
     */
    public function testGetStylesByTagWorks()
    {
        $res = $this->repository->getStylesByTag('Brooklyn');

        $this->assertEquals(1, count($res));
        $this->assertEquals([$this->documents[3]], $res);

    }

    /**
     * @group Models
     */
    public function testGetStylesBySearchWorksMatchInTag()
    {
        $expectedDocs[] = $this->documents[0];
        $expectedDocs[] = $this->documents[4];
        $expectedDocs[] = $this->documents[5];

        $res = $this->repository->getStylesBySearch('microblading');

        $this->assertEquals(3, count($res));
        $this->assertEquals($expectedDocs, $res);
    }

    /**
     * @group Models
     */
    public function testGetStylesBySearchWorksMatchInName()
    {
        $res = $this->repository->getStylesBySearch('gold');

        $this->assertEquals(1, count($res));
        $this->assertEquals([$this->documents[2]], $res);
    }

    /**
     * @group Models
     */
    public function testGetStylesBySearchWorksMatchInDescription()
    {
        $res = $this->repository->getStylesBySearch('dead');

        $this->assertEquals(1, count($res));
        $this->assertEquals([$this->documents[3]], $res);
    }
}
