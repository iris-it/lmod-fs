<?php
/**
 * Created by PhpStorm.
 * User: alexa
 * Date: 13/02/2018
 * Time: 11:20
 */

namespace Irisit\Filestash\Helpers;

class PropertiesHelper
{


    public static function getStubs()
    {
        return [
            "display_name" => "",
            "owner" => "",
            "view_directory" => [],
            "view_files" => [],
            "read" => [],
            "write" => [],
            "delete" => [],
            "share" => [],
        ];
    }

    public static function getStubType($key)
    {
        $types = [
            "display_name" => "string",
            "owner" => "string",
            "view_directory" => "array",
            "view_files" => "array",
            "read" => "array",
            "write" => "array",
            "delete" => "array",
            "share" => "array",
        ];

        return $types[$key];
    }

    public static function validate($properties)
    {
        foreach ($properties as $key => $value) {
            switch (self::getStubType($key)) {
                case 'string':
                    if (!is_string($value)) {
                        return false;
                    }
                    break;
                case 'array':
                    if (!is_array($value)) {
                        return false;
                    }
                    break;
                default:
                    return false;
                    break;
            }
        }

        return true;
    }


}