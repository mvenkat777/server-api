<?php

namespace Platform\Uploads\Transformers;

use Platform\Uploads\Models\Upload;

class FileTransformer
{
    /**
     * @param  Upload $fileObj 
     * @return mixed          
     */
    public function transform(Upload $fileObj)
    {
        $file = [
            'id' => $fileObj->id,
            'mimeType' => $fileObj->mime_type,
            'name' => $fileObj->name,
            'selfLink' => $fileObj->self_link,
            'title' => $fileObj->title,
            'webViewLink' => $fileObj->web_link,
            'webViewAllSizesLinks' => json_decode($fileObj->web_link_sizes),
            'description' => $fileObj->description,
            'size' => $fileObj->size,
            'extension' => $fileObj->extension,
            ];
        return $file;
    }
}
