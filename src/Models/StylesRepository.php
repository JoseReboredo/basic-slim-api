<?php
/**
 * Created by IntelliJ IDEA.
 * User: jose
 * Date: 27/08/18
 * Time: 11:49
 */

namespace SlimApi\Models;

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
    }

    /**
     * Get all the available styles
     */
    public function getStyles()
    {
        // TODO: Implement getStyles() method.
    }
}
