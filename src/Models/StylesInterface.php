<?php
/**
 * Created by IntelliJ IDEA.
 * User: jose
 * Date: 27/08/18
 * Time: 14:02
 */

namespace SlimApi\Models;

use SlimApi\Models\RepositoryException\RepositoryException;

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
     * @return array
     * e.g.
     * [
     *  [
     *  "id" => "2e6c0330-bb8a-4560-aa42-ad49397ad236",
     *  "name" => "Nano Hair-strokes: Full Brow",
     *  "tags" => "Eyebrows,brows,perfectbrows,microblading,nanoblading,Permanentmakeup",
     *  "description" => "Full brow hair-stroke application = maximum volume, definition, and symmetry.",
     *  "status" => "PUBLISHED",
     *  "dateCreated" => "2018-08-18 22:01:31",
     *  "deleted" => 0
     *  ],
     * ...
     *]
     * @throws RepositoryException
     */
    public function getAllStyles();

    /**
     * Get all styles that match a given tag
     *
     * @param string $tag
     * @return array
     * e.g.
     * [
     *  [
     *  "id" => "2e6c0330-bb8a-4560-aa42-ad49397ad236",
     *  "name" => "Nano Hair-strokes: Full Brow",
     *  "tags" => "Eyebrows,brows,perfectbrows,microblading,nanoblading,Permanentmakeup",
     *  "description" => "Full brow hair-stroke application = maximum volume, definition, and symmetry.",
     *  "status" => "PUBLISHED",
     *  "dateCreated" => "2018-08-18 22:01:31",
     *  "deleted" => 0
     *  ],
     * ...
     *]
     * @throws RepositoryException
     */
    public function getStylesByTag($tag);

    /**
     * Get all styles that match a search
     *
     * @param string $value
     * @return array
     * e.g.
     * [
     *  [
     *  "id" => "2e6c0330-bb8a-4560-aa42-ad49397ad236",
     *  "name" => "Nano Hair-strokes: Full Brow",
     *  "tags" => "Eyebrows,brows,perfectbrows,microblading,nanoblading,Permanentmakeup",
     *  "description" => "Full brow hair-stroke application = maximum volume, definition, and symmetry.",
     *  "status" => "PUBLISHED",
     *  "dateCreated" => "2018-08-18 22:01:31",
     *  "deleted" => 0
     *  ],
     * ...
     *]
     * @throws RepositoryException
     */
    public function getStylesBySearch($value);
}
