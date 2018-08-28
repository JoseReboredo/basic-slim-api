<?php
/**
 * Created by IntelliJ IDEA.
 * User: jose
 * Date: 27/08/18
 * Time: 11:49
 */

namespace SlimApi\Models;

use MongoDB\BSON\Regex;
use MongoDB\Collection;
use MongoDB\Driver\Cursor;
use SlimApi\Models\RepositoryException\RepositoryException;

/**
 * Class StylesRepository
 *
 * @package SlimApi\Models
 */
class StylesRepository implements StylesInterface
{
    /**
     * @var Collection
     */
    protected $collection;

    /**
     * StylesRepository constructor.
     *
     * @param Collection $collection
     */
    public function __construct(Collection $collection)
    {
        $this->collection = $collection;

        $this->collection->createIndex(['tags' => 1]);
        $this->collection->createIndex(['name' => 1]);
        $this->collection->createIndex(['description' => 1]);
    }

    /**
     * Get all the available styles
     *
     * @return array
     * @throws RepositoryException
     */
    public function getAllStyles()
    {
        try {
        $documents = $this->collection->find([],['projection' => ['_id' => 0]])->toArray();
        } catch (\Exception $e) {
            throw new RepositoryException('Issue withe MongoDB:' . $e->getMessage());
        }
        return $this->formatDocuments($documents);
    }

    /**
     * Get all styles that match a given tag
     *
     * @param string $tag
     * @return array
     * @throws RepositoryException
     */
    public function getStylesByTag($tag)
    {
        try {
        $documents = $this->collection->find(
            [
                'tags' => new Regex('^'.$tag.'$', 'i')
            ],
            [
                'projection' => ['_id' => 0]
            ]
        )->toArray();
        } catch (\Exception $e) {
            throw new RepositoryException('Issue withe MongoDB:' . $e->getMessage());
        }
        return $this->formatDocuments($documents);
    }

    /**
     * Get all styles that match a search in name, tag or description
     *
     * @param string $value
     * @return array
     * @throws RepositoryException
     */
    public function getStylesBySearch($value)
    {
        try {
            $documents = $this->collection->find(
                [
                    '$or' => [
                        ['tags' => new Regex('^' . $value . '$', 'i')],
                        ['name' => new Regex($value, 'i')],
                        ['description' => new Regex($value, 'i')],
                    ]
                ],
                [
                    'projection' => ['_id' => 0]
                ]
            )->toArray();
        } catch (\Exception $e) {
            throw new RepositoryException('Issue withe MongoDB:' . $e->getMessage());
        }

        return $this->formatDocuments($documents);
    }

    /**
     * Format the documents returned by th DB
     *
     * @param array $documents
     * @return array
     */
    private function formatDocuments(array $documents)
    {
        foreach ($documents as &$doc) {
            if (isset($doc['tags'])) {
                $doc['tags'] = implode(',', $doc['tags']);
            }
        }
        return $documents;
    }
}
