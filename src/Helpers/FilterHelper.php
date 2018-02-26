<?php
/**
 * Created by PhpStorm.
 * User: alexa
 * Date: 13/02/2018
 * Time: 11:20
 */

namespace Irisit\Filestash\Helpers;


use CallbackFilterIterator;
use RecursiveArrayIterator;
use RecursiveCallbackFilterIterator;
use RecursiveFilterIterator;
use RecursiveIteratorIterator;
use RecursiveTreeIterator;

class FilterHelper
{

    /**
     * This method filter a list of items and remove
     * items if those are dot files
     *
     * @param array $items
     * @return array
     */
    public static function itemNotDotFile(array $items)
    {

        foreach ($items as $key => $item) {
            if (substr($item['basename'], 0, 1) === '.') {
                unset($items[$key]);
            }
        }

        return $items;

    }

    /**
     * This method filter a list of items that contains a
     * variable type with possible values ['file', 'dir', 'all']
     *
     * required param array $items
     * optional param string $type = 'all' | ['file', 'dir', 'all']
     *
     * @param array $items
     * @param string $type
     * @return array
     */
    public static function itemType(array $items, $type = 'all')
    {

        if ($type !== 'all') {
            foreach ($items as $key => $item) {
                if ($item['type'] !== $type) {
                    unset($items[$key]);
                }
            }
        }

        return $items;

    }

    /**
     * This method filter a list of items based on the
     * identifier / groups / is_admin of an user
     *
     * required param array $items
     * required param string $identifier ( user identifier )
     * required param array $groups ( user groups list )
     * optional param bool $is_admin
     *
     * @param array $items
     * @param string $identifier
     * @param array $groups
     * @param bool $is_admin
     * @return array
     */
    public static function itemAuthorized(array $items, string $identifier, array $groups, bool $is_admin = false)
    {

        if ($is_admin) {
            return $items;
        }

        foreach ($items as $key => $item) {

            if ($identifier === $item['properties']['owner']) {
                continue;
            }

            if ($item['type'] === 'dir' && sizeof(array_intersect($groups, $item['properties']['view_directory'])) > 0) {
                continue;
            }

            if ($item['type'] === 'file' && sizeof(array_intersect($groups, $item['properties']['view_files'])) > 0) {
                continue;
            }

            unset($items[$key]);

        }

        return $items;

    }

}
