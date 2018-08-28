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
     */
    public function getAllStyles()
    {
        $cursor = $this->collection->find([],['projection' => ['_id' => 0]]);
        return $this->formatDocuments($cursor);
    }

    /**
     * Get all styles that match a given tag
     *
     * @param string $tag
     * @return array
     */
    public function getStylesByTag($tag)
    {
        $cursor = $this->collection->find(
            [
                'tags' => new Regex('^'.$tag.'$', 'i')
            ],
            [
                'projection' => ['_id' => 0]
            ]
        );
        return $this->formatDocuments($cursor);
    }

    /**
     * Get all styles that match a search in name, tag or description
     *
     * @param string $value
     * @return array
     */
    public function getStylesBySearch($value)
    {
        $cursor = $this->collection->find(
            [
                '$or' => [
                    ['tags' => new Regex('^'.$value.'$', 'i')],
                    ['name' => new Regex($value, 'i')],
                    ['description' => new Regex($value, 'i')],
                ]
            ],
            [
                'projection' => ['_id' => 0]
            ]
        );
        return $this->formatDocuments($cursor);
    }

    /**
     * Format the documents returned by th DB
     *
     * @param Cursor $cursor
     * @return array
     */
    private function formatDocuments(Cursor $cursor)
    {
        $cursor->setTypeMap([
            'root' => 'array',
            'document' => 'array',
            'array' => 'array'
        ]);
        $documents = $cursor->toArray();

        foreach ($documents as &$doc) {
            if (isset($doc['tags'])) {
                $doc['tags'] = implode(',', $doc['tags']);
            }
        }
        return $documents;
    }
}
