<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Wrappers\AwsS3Wrapper;
use Platform\Uploads\Commands\DeleteFileCommand;
use Platform\Uploads\Commands\UploadFileCommand;
use Platform\Uploads\Repositories\Contracts\UploadRepository;
use Platform\Uploads\Transformers\FileTransformer;
use Platform\Uploads\Validators\UploadPublic;
use Platform\Uploads\Validators\Uploads;

class UploadController extends ApiController 
{
	/**
	 * Platform\App\Commanding\DefaultCommandBus 
	 * @var command
	 */
	protected $commandBus;

	/**
	 * @var Platform\Uploads\Validators\Uploads
	 */
	protected $upload;

	/**
	 * @var Platform\Uploads\Repositories\Contracts\UploadRepository
	 */
	protected $uploadRepository;

	/**
	 * @var Platform\Uploads\Validators\UploadPublic
	 */
	protected $uploadPublic;

	/**
	 * @var Platform\App\Wrappers\AwsS3Wrapper
	 */
	protected $aswS3Wrapper;

	/**
	 * @param Platform\App\Commanding\DefaultCommandBus $commandBus       
	 * @param Platform\Uploads\Repositories\Contracts\UploadRepository  $uploadRepository 
	 * @param Platform\Uploads\Validators\UploadPublic      $uploadPublic     
	 * @param Platform\App\Wrappers\AwsS3Wrapper      $awsS3Wrapper     
	 * @param Platform\Uploads\Validators\Uploads           $upload           
	 */
	function __construct(
		DefaultCommandBus $commandBus,
		UploadRepository $uploadRepository,
		UploadPublic $uploadPublic,
		AwsS3Wrapper $awsS3Wrapper,
		Uploads $upload
	) {
		$this->upload = $upload;
		$this->awsS3Wrapper = $awsS3Wrapper;
		$this->uploadPublic = $uploadPublic;
		$this->uploadRepository = $uploadRepository;
	    $this->commandBus = $commandBus;

	    parent::__construct(new Manager());
    }

    /**
     * @return mixed
     */
	public function getUpload()
	{
		return $this->respondWithCollection($this->uploadRepository->getAllFiles(), 
					new FileTransformer, 'Files'
				);
	}

	/**
	 * @param  Request $request 
	 * @return mixed
	 */
	public function postUpload(Request $request)
	{
		$input = $request->all();
		$this->upload->validate($input);
        $response = $this->commandBus->execute(new UploadFileCommand($input));
        if(count($response) == 1){	
        	return $this->respondWithNewItem($response[0], new FileTransformer, 'file');
		}
		return $this->respondWithCollection($response, new FileTransformer, 'file');
	}

	/**
	 * @param Request $request 
	 * @param $fileId  
	 */
	public function setPublic(Request $request, $fileId)
	{
		$data = $request->all();
		$this->uploadPublic->validate($data);
		if($this->uploadRepository->setIsPublic($fileId, $data)){
			return $this->respondOk('Updated Successfully');
		}
		return $this->respondWithError('Updation Faild');
	}

	/**
	 * @return mixed
	 */
	public function getPublic()
	{
		return $this->respondWithCollection(
					$this->uploadRepository->getPublicFiles(),
					new FileTransformer, 'file'
				);
	}

	/**
	 * @param  Request $request 
	 * @return mixed           
	 */
	public function download(Request $request)
	{
		$data = $request->all();
		//get dowload link from S3
    	try{
	    	$array = explode('/', explode('https://s3.amazonaws.com/', $data['link'])[1]);
	    	$downloadUrl = $this->awsS3Wrapper->setBucket($array[0])
	    						->setFolder($array[1].'/'.$array[2])
	    						->downloadLink($array[3]);
	    }
	    catch(Exception $e){
	    	throw new SeException('Unable to upload', 403, 4030100);
	    }
		return $this->respondWithArray(['data' => ['downloadLink' => $downloadUrl]]);
	
	}

	/**
	 * @param  Request $request 
	 * @param  fileId $id      
	 * @return string          
	 */
	public function deleteFile(Request $request, $id){
        $response = $this->commandBus->execute(new DeleteFileCommand($id));
        if($response){
        	return $this->respondOk('Deleted Successfully');
        }
        return $this->respondWithError('Deletion Failed');
	}
}
