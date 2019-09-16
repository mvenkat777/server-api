<?php

namespace Platform\Uploads\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\App\Wrappers\AwsS3Wrapper;
use Platform\Uploads\Helpers\UploadHelpers;
use Platform\Uploads\Repositories\Contracts\UploadRepository;
use Platform\Uploads\Transformers\FileTransformer;

class DeleteFileCommandHandler implements CommandHandler
{
	/**
	 * @var Platform\App\Wrappers\AwsS3Wrapper
	 */
	protected $aswS3Wrapper;

	/**
	 * @var Platform\Uploads\Repositories\Contracts\UploadRepository
	 */
	protected $upload;

    /**
     * @param Platform\App\Wrappers\AwsS3Wrapper     $aswS3Wrapper 
     * @param Platform\Uploads\Repositories\Contracts\UploadRepository $upload       
     */
    public function __construct(AwsS3Wrapper $aswS3Wrapper, UploadRepository $upload) 
    {
    	$this->aswS3Wrapper = $aswS3Wrapper;
    	$this->upload = $upload;
    }

    /**
     * @param  Platform\Uploads\Commands\DeleteFileCommand
     * @return mixed
     */
    public function handle($command)
    {
    	$file = $this->upload->getFile($command->fileId);
    	if(!is_null($file)){
	    	$this->deleteFromS3($file);
	    	return $this->upload->deleteUploadedFile($command->fileId);
	    }
	    	throw new SeException('Unprocessable intity', 400, 4030101);
    }

    /**
    * @param  array $fileDetails 
    * @return mixed
    */
    private function deleteFromS3($fileDetails)
    {
    	//delete from S3
    	try{
    		$array = explode('/', explode('https://s3.amazonaws.com/', $fileDetails['self_link'])[1]);
	    	return $this->aswS3Wrapper->setBucket($array[0])
	    						->setFolder($array[1].'/'.$array[2])
	    						->delete($array[3]);
	    }
	    catch(Exception $e){
	    	throw new SeException('Unable to delete', 403, 4030100);
	    }
    }
}