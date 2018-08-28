<?php
/**
 * Created by IntelliJ IDEA.
 * User: jose
 * Date: 27/08/18
 * Time: 14:52
 */

namespace SlimApi\Tests\Unit\Models;

use MongoDB\Collection;
use MongoDB\Driver\Cursor;
use MongoDB\Driver\Exception\RuntimeException;
use PHPUnit\Framework\TestCase;
use SlimApi\Models\RepositoryException\RepositoryException;
use SlimApi\Models\StylesInterface;
use SlimApi\Models\StylesRepository;

/**
 * Class StylesRepositoryTest
 *
 * @package SlimApi\Tests\Unit\Models
 */
class StylesRepositoryTest extends TestCase
{
    /**
     * @var StylesInterface
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
        $this->collection = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->setMethods(['find', 'createIndex'])
            ->getMock();
        $this->collection
            ->method('createIndex')
            ->willReturn(true);

        $this->repository = new StylesRepository($this->collection);
    }

    /**
     * @group Models
     */
    public function testFormattingOfDBDocumentsWorks()
    {
        $expectedDocuments = [
            [
                "id" => "2e6c0330-bb8a-4560-aa42-ad49397ad236",
                "name" => "Nano Hair-strokes: Full Brow",
                "tags" => "Eyebrows,brows,perfectbrows,microblading,nanoblading,Permanentmakeup",
                "description" => "Full brow hair-stroke application = maximum volume, definition, and symmetry.",
                "status" => "PUBLISHED",
                "dateCreated" => "2018-08-18 22:01:31",
                "deleted" => 0
            ]
        ];

        $docs = $expectedDocuments;
        $docs[0]['tags'] = explode(',', $expectedDocuments[0]['tags']);

        $this->collection
            ->method('find')
            ->willReturn($docs);

        $result = $this->repository->getAllStyles();

        $this->assertEquals($expectedDocuments, $result);
    }

    /**
     * @group Models
     */
    public function testRepositoryExceptionIsThrown()
    {
        $this->expectException(RepositoryException::class);

        $this->collection
            ->method('find')
            ->willThrowException(new RuntimeException('Fake exception'));

        $this->repository->getAllStyles();
    }
}
