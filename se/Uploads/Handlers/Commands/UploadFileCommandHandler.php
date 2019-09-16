<?php

namespace Platform\Uploads\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\App\Wrappers\AwsS3Wrapper;
use Platform\Uploads\Helpers\UploadHelpers;
use Platform\Uploads\Repositories\Contracts\UploadRepository;
use Platform\Uploads\Transformers\FileTransformer;
use Image;
use Platform\Uploads\Helpers\ImageResizerHelpers;
use Platform\Uploads\Jobs\ResizerJob;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Bus\DispatchesJobs;

class UploadFileCommandHandler implements CommandHandler
{
    use DispatchesJobs;
	/**
	 * @var Platform\App\Wrappers\AwsS3Wrapper
	 */
	protected $aswS3Wrapper;

	/**
	 * @var Platform\Uploads\Repositories\Contracts\UploadRepository
	 */
	protected $upload;

    /**
     * @var Platform\Uploads\Helpers\ImageResizerHelpers
     */
    protected $imageResizer;


    public function __construct(
        AwsS3Wrapper $aswS3Wrapper,
        UploadRepository $upload,
        ImageResizerHelpers $imageResizer
    ) {
    	$this->aswS3Wrapper = $aswS3Wrapper;
    	$this->upload = $upload;
        $this->imageResizer = $imageResizer;
    }

    /**
     * @param  Platform\Uploads\Commands\UploadFileCommand
     * @return mixed
     */
    public function handle($command)
    {  //dd($command->files);
        //dd(base_path().'/storage/uploads/');
        
    	foreach ($command->files as $key => $file) {
	    	$fileDetails = $this->getFileDetails($file);
            // var_dump($fileDetails);
            // dd($file);
            // dd($command->files[0]);
            // var_dump("---original---", $originalFilePath);
            // var_dump("---valid----", $validSizes);
            // $validSizes = [];
            if(substr($fileDetails['mimeType'], 0, 5) == 'image'){
                $originalFilePath = $this->imageResizer->makeMainFileLocalCopy($file,$fileDetails['name']);
                $validSizes = $this->imageResizer->getValidImageSizes($file);
                if(count($validSizes) > 0 ){
                    $resizeJob = new ResizerJob($fileDetails['name'], $originalFilePath, json_encode($command));
                    $this->dispatch($resizeJob);
                }
               
               //$this->imageResizer->makeImagesSizesLocalCopyAndUpload($fileDetails['name'],$originalFilePath,$command);             
            }
            //$remoteUrls = ImageResizerHelpers::makeImagesSizesLocalCopyAndUpload($fileDetails['name'],$file,$command);
            // var_dump('STOPEED');
	    	$uploadedFile = $this->uploadToS3($fileDetails, $command);
	    	$fileDetails['selfLink'] = $uploadedFile['ObjectURL'];
            // var_dump($remoteUrls);
            //Remote Urls parsing
            //dd($this->uploadImageSizes);
            if(isset($validSizes)){
                foreach($validSizes as $imgSizes){

                    $fileUrl = substr($fileDetails['selfLink'], 0, strrpos($fileDetails['selfLink'], '.'));
                    $extension = substr($fileDetails['selfLink'], strrpos($fileDetails['selfLink'], '.') + 1);
                    $selfLinkArr = explode('.',$fileDetails['selfLink']);
                    $fileDetails['selfLink'.ucfirst($imgSizes)] = $fileUrl.'_'.$imgSizes.'.'.$extension;
                
                }
            }
            //dd($fileDetails);
            //ImageResizerHelpers::removeFile($originalFilePath);
	    	$result[$key] = $this->upload->addUploadedFile($fileDetails, $command);
            //dd($result);
    	}
        // var_dump()
    	return $result;
    }

    /**
     * Getting the file details
     * @param   object $file
     * @return array
     */
    private function getFileDetails($file)
    {
        if(filesize($file) > 104857600){
            throw new SeException("File size is too large", 413, 4030102);
        }
    	$fileDetails['name'] = $file->getClientOriginalName() . '.' . $file->getClientOriginalExtension();
		$fileDetails['extension'] = $file->getClientOriginalExtension();
		$fileDetails['title'] = str_replace('.'.$fileDetails['extension'], '', $fileDetails['name']);
		$fileDetails['name'] = date("DMjGisY")."".rand(1000,9999).".".$fileDetails['extension'];
		$fileDetails['mimeType'] = $file->getMimeType();
		$fileDetails['webLink'] = UploadHelpers::generateUrl($fileDetails['name']);
		$fileDetails['filePath'] = $file->getRealPath();
		$fileDetails['size'] = intval($file->getSize() / 1024);
		
        return $fileDetails;
    }

   /**
    * @param  array $fileDetails [description]
    * @param  Platform\Uploads\Commands\UploadFileCommand $command
    * @return mixed
    */
    private function uploadToS3($fileDetails , $command)
    {
    	//upload to S3
    	try{
	    	return $this->aswS3Wrapper->setBucket($command->bucket)
	    						->setFolder($command->folder)
	    						->upload($fileDetails);
	    }
	    catch(Exception $e){
	    	throw new SeException('Unable to upload', 403, 4030100);
	    }
    }


    /*private function makeImagesSizesLocalCopyAndUpload($filename,$file,$command)
    {
            $timestampName = explode('.',$filename);
            //dd(base_path());
            //dd($this->uploadImageSizes);

            $baseDir = base_path().$this->localUploadDirectory;
            $uploadedUrls = [];
            foreach($this->uploadImageSizes as $sizes){
                
                $fileLoc = $baseDir.$timestampName[0].'_'.$sizes['label'].'.'.$timestampName[1];

                // LOCAL COPY 
                \Image::make($file)->resize($sizes['width'], $sizes['height'])->save($fileLoc);
                
                //REMOTE UPLOAD
                try{
                    $uploadedFile = $this->aswS3Wrapper->setBucket($command->bucket)
                                    ->setFolder($command->folder)
                                    ->uploadFromPath($fileLoc);
                    
                    $uploadedUrls[$sizes['label']] = $uploadedFile['ObjectURL'];                
                }catch(Exception $e){
                    throw new SeException('Unable to upload', 403, 4030100);
                }
            
            }

        return $uploadedUrls;            
    }*/
}
