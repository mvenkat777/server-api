<?php

namespace Platform\Uploads\Repositories\Eloquent;

use Illuminate\Support\Facades\Hash;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\Uploads\Models\Upload;
use Platform\Uploads\Repositories\Contracts\UploadRepository;

class EloquentUploadRepository extends Repository implements UploadRepository
{
    /**
     * @return Platform\Uploads\Models\Upload
     */
    public function model()
    {
        return 'Platform\Uploads\Models\Upload';
    }

    /**
     * @return mixed
     */
    public function getAllFiles()
    {
        return $this->model->all();
    }

    /**
     * @param  array $data
     * @param  UploadFileCommand $command
     * @return mixed
     */
    public function addUploadedFile($data, $command)
    {

        $imgData = ['web_link_large'  => isset($data['selfLinkLarge'])?$data['selfLinkLarge']:$data['selfLink'],
                    'web_link_medium'  => isset($data['selfLinkMedium'])?$data['selfLinkMedium']:$data['selfLink'],
                    'web_link_thumbnail'  => isset($data['selfLinkThumbnail'])?$data['selfLinkThumbnail']:$data['selfLink']
                   ];
        $file = [
            'name' => $data['name'],
            'title' => $data['title'],
            'self_link' => $data['selfLink'],
            'web_link'  => $data['selfLink'],
            'web_link_sizes' => json_encode($imgData),
            'is_public' => $command->isPublic,
            'mime_type'   => $data['mimeType'] ,
            'extension' => $data['extension'],
            'description' => $command->description,
            'size' => $data['size']
        ];
        return $this->model->create($file);
    }

    /**
     * @param integer $fileId 
     * @param array $data   
     */
    public function setIsPublic($fileId , $data)
    {
        return $this->model->where('id', '=', $fileId)
                            ->update(['is_public' => $data['isPublic']]);
    }

    /**
     * @return mixed
     */
    public function getPublicFiles()
    {
        return $this->model->where('is_public', '=', true)->get();
    }

    /**
     * @param  integer $fileId 
     * @return mixed     
     */
    public function getFile($fileId)
    {
        return $this->model->where('id', '=', $fileId)->first();
    }

    /**
     * @param  integer $fileId 
     * @return mixed     
     */
    public function deleteUploadedFile($fileId)
    {
        return $this->model->where('id', '=', $fileId)->delete();
    }
}
