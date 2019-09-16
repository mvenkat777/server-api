<?php

namespace Platform\Uploads\Helpers;
use Platform\App\Exceptions\SeException;
use Platform\App\Wrappers\AwsS3Wrapper;
use Image;

class ImageResizerHelpers
{
		
	/**
     * @var Upload location in local
     */
    public static $localUploadDirectory = '/storage/uploads/';

    /**
     * @var Get Image sizes configured
     */
    public static function getImageSizes(){
    	return [ 
                ['label' => 'thumbnail','width' => 100,'height' => 100 ],
                ['label' => 'medium','width' => 640,'height' => 480 ],
                ['label' => 'large','width' => 1024,'height' => 768 ]
               ];                           
    }


	public function getAwswrapper(){
    	return new AwsS3Wrapper();                        
    }

    public function makeMainFileLocalCopy($file,$fileName){
    	//dd($fileName);
    	$fileSavedLocation = base_path().self::$localUploadDirectory.$fileName;
    	\Image::make($file)->save($fileSavedLocation);
    	return $fileSavedLocation;                      
    }

    public function getValidImageSizes($file){
    	$finalImageLabels = [];
    	$configSizes = self::getImageSizes();
    	$orginalFileWidth = \Image::make($file)->width();
        $orginalFileHeight = \Image::make($file)->height();
        //dd($orginalFileWidth."X".$orginalFileHeight);
    	foreach($configSizes as $sizes){
    		if($orginalFileWidth >= $sizes['width'] && $orginalFileHeight >= $sizes['height'] ){
    			$finalImageLabels[] = $sizes['label'];
    		}
    	}

    	return $finalImageLabels;                          
    }
    

	public function makeImagesSizesLocalCopyAndUpload($filename,$file,$command)
    {		
    		$wrapper = $this->getAwswrapper();
    		$configSizes = self::getImageSizes();
    		$validImageLabels = $this->getValidImageSizes($file);
    		//dd($validImageLabels);
            
            $timestampName = explode('.',$filename);
            //dd(base_path());
            //dd($configSizes);
            $baseDir = base_path().self::$localUploadDirectory;
            $uploadedUrls = [];
            
            foreach($configSizes as $sizes){
                
                if(in_array($sizes['label'], $validImageLabels)){

                	$fileLoc = $baseDir.$timestampName[0].'_'.$sizes['label'].'.'.$timestampName[1];
                
                	// LOCAL COPY 
	                \Image::make($file)->resize($sizes['width'], $sizes['height'])->save($fileLoc);
	                
	                //REMOTE UPLOAD
	                try{
	                    $uploadedFile = $wrapper->setBucket($command->bucket)
	                                    ->setFolder($command->folder)
	                                    ->uploadFromPath($fileLoc);
	                    
	                    $uploadedUrls[$sizes['label']] = $uploadedFile['ObjectURL'];                
	                }catch(Exception $e){
	                    throw new SeException('Unable to upload', 403, 4030100);
	                }

	                //Remove resized file
	                $this->removeFile($fileLoc);

                }
                
            
            }

            //Remove original file
	        $this->removeFile($file);

        return $uploadedUrls;            
    }

    public function removeFile($file)
    {
    	if(file_exists($file)) {
	        @unlink($file);
	    }
    }

}