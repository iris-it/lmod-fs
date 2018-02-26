<?php
/**
 * Created by PhpStorm.
 * User: alexa
 * Date: 13/02/2018
 * Time: 11:20
 */

namespace Irisit\Filestash\Helpers;


class DirectoryHelper
{

    /**
     * Join two paths with respect of the separator
     *
     * @param $one
     * @param $two
     * @return string
     */
    public static function combinePaths($one, $two)
    {
        return htmlspecialchars($one . preg_replace('/^\/?/', '/', $two));
    }

    /**
     * Prevent the traversal of paths like ../../../
     * @param $path
     * @return string
     */
    public static function preventTraversal($path)
    {
        $root = '/';

        $segments = explode('/', trim($path, '/'));

        $return = [];

        foreach ($segments as $segment) {
            if (($segment == '.') || strlen($segment) === 0) {
                continue;
            }
            if ($segment == '..') {
                array_pop($return);
            } else {
                array_push($return, $segment);
            }
        }

        return $root . implode('/', $return);
    }

}