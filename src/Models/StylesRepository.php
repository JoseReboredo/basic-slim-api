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
        return $this->collection->find()->toArray();
    }

    /**
     * Get all styles that match a given tag
     *
     * @param string $tag
     * @return array
     */
    public function getStylesByTag($tag)
    {
        return $this->collection->find(['tags' => new Regex('^'.$tag.'$', 'i')])->toArray();
    }

    /**
     * Get all styles that match a search in name, tag or description
     *
     * @param string $value
     * @return array
     */
    public function getStylesBySearch($value)
    {
        return $this->collection->find(
            [
                '$or' => [
                    ['tags' => new Regex('^'.$value.'$', 'i')],
                    ['name' => new Regex('^'.$value.'$', 'i')],
                    ['search' => new Regex('^'.$value.'$', 'i')],
                ]
            ]
        )->toArray();
    }
}
