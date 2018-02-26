<?php
/**
 * Created by PhpStorm.
 * User: alexa
 * Date: 13/02/2018
 * Time: 11:20
 */

namespace Irisit\Filestash\Helpers;

class TreeHelper
{

    /**
     * This method builds a tree or a flat list based
     * on the file input
     *
     * required param array $items (files)
     * required param string $current_path
     *
     * @param array $items
     * @param $current_path
     * @return array
     */
    public static function build(array $items, $current_path)
    {
        $current_path = ltrim($current_path, '/');

        $tree = self::buildTree($items, $current_path);

        return $tree;
    }

    public static function buildTree(array $elements, $current_path)
    {
        $branch = array();

        foreach ($elements as $element) {

            if ($element['dirname'] == $current_path) {

                $children = self::buildTree($elements, $element['path']);

                if ($children) {
                    $element['nodes'] = $children;
                }

                $branch[] = $element;
            }
        }

        return $branch;
    }


}