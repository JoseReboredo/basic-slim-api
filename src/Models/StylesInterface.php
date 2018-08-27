<?php
/**
 * Created by IntelliJ IDEA.
 * User: jose
 * Date: 27/08/18
 * Time: 14:02
 */

namespace SlimApi\Models;


/**
 * Interface StylesInterface
 *
 * @package SlimApi\Models
 */
interface StylesInterface
{
    /**
     * Return all the published styles that are not deleted
     *
     * @return
     */
    public function getAllStyles();

    /**
     * Get all styles that match a given tag
     *
     * @param string $tag
     * @return array
     */
    public function getStylesByTag($tag);

    /**
     * Get all styles that match a search
     *
     * @param string $value
     */
    public function getStylesBySearch($value);
}
