<?php

namespace Platform\Uploads\Repositories\Contracts;

interface UploadRepository
{	
	/**
     * @return Platform\Uploads\Models\Upload
     */
    public function model();

    /**
     * @return mixed
     */
    public function getAllFiles();

    /**
     * @param  array $data
     * @param  UploadFileCommand $command
     * @return mixed
     */
    public function addUploadedFile($data, $command);

    /**
     * @param integer $fileId 
     * @param array $data   
     */
    public function setIsPublic($fileId , $data);

    /**
     * @return mixed
     */
    public function getPublicFiles();

    /**
     * @param  integer $fileId 
     * @return mixed     
     */
    public function getFile($fileId);

    /**
     * @param  integer $fileId 
     * @return mixed     
     */
    public function deleteUploadedFile($fileId);
}
