<?php

namespace Platform\Cloud\Transformers;

class FileTransformer
{
    public function transform($fileObj)
    {
        $arr = [
            'id' => $fileObj->id,
            'mimeType' => $fileObj->mime_type,
            'name' => $fileObj->name,
            'selfLink' => $fileObj->self_link,
            'title' => $fileObj->title,
            'webViewLink' => $fileObj->web_link,
            'description' => $fileObj->description,
            'extension' => $fileObj->extension,
            ];
        //return json_decode(json_encode($arr));
        return $arr;
    }
}
