<?php
/**
 * Created by PhpStorm.
 * User: alexa
 * Date: 12/02/2018
 * Time: 14:07
 */

namespace Irisit\Filestash\Transformers;


use League\Fractal\TransformerAbstract;

class FileTransformer extends TransformerAbstract
{

    public function transform($file)
    {
        $item = [];

        if ($file['type'] === 'file') {
            $item['extension'] = $file['extension'];
            $item['size'] = $file['size'];
            $item['mimetype'] = $file['mimetype'];
        }

        $item['type'] = $file['type'];
        $item['path'] = $file['path'];
        $item['timestamp'] = $file['timestamp'];
        $item['dirname'] = $file['dirname'];
        $item['basename'] = $file['basename'];
        $item['filename'] = $file['filename'];

        $item['properties'] = $file['properties'];

        if (isset($file['nodes'])) {

            foreach ($file['nodes'] as $key => $value) {
                $item['nodes'][] = $this->transform($value);
            }

            return $item;

        } else {

            return $item;

        }
    }
}