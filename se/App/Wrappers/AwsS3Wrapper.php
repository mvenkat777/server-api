<?php

namespace Platform\App\Wrappers;

use Platform\Uploads\Helpers\UploadHelpers;

class AwsS3Wrapper
{
	CONST STAGING_BUCKET = 'sourceasy-public';
	CONST PRODUCTION_BUCKET = 'sourceasy-public';
	CONST STAGING_FOLDER = 'testing';
	CONST PRODUCTION_FOLDER = 'production';
	CONST LOCAL_FOLDER = 'testing';

	/**
	 * @var string
	 */
	private $bucket;

	/**
	 * @var string
	 */
	private $folder;

	/**
	 * @param  	$fileDetails
	 * @return 	mixed
	 */
	public function upload($fileDetails)
	{
		$s3 = \App::make('aws')->createClient('s3');
		$response = $s3->putObject(array(
		    'Bucket'     => $this->getBucket(),
		    'Key'        => $this->getKey($fileDetails['name']),
		    'SourceFile' => $fileDetails['filePath'],
		    'ContentType' => $fileDetails['mimeType'],
		    'ACL' => 'public-read'
		  ));
		//dd($response);
		return $response->toArray();
	}

	/**
	 * Upload a file from path
	 * @param  String $filePath
	 * @return mixed
	 */
	public function uploadFromPath($filePath)
	{
		$file['name'] = \File::name($filePath) . '.' . \File::extension($filePath);
        $file['extension'] = \File::extension($filePath);
        $file['title'] = \File::name($filePath);
        $file['mimeType'] = \File::mimeType($filePath);
        $file['webLink'] = $file['name'] ;//. '.' . $file['extension'];
        $file['filePath'] = $filePath;
        //dd($file);
        return $this->upload($file);
	}

	/**
	 * @param  	FileName $name
	 * @return 	mixed
	 */
	public function delete($name)
	{
	 	$s3 = \App::make('aws')->createClient('s3');
        $response = $s3->deleteObject([
			'Bucket' => $this->getBucket(),
			'Key' => $this->getFolder().'/'.$name
        ]);
        return $response;
	}

	/**
	 * @param 	filename $name
	 * @return 	mixed
	 */
	public function downloadLink($name)
	{
		$s3 = \App::make('aws')->createClient('s3');
		$response = $s3->getObjectUrl(
			$this->getBucket(),
			$this->getFolder().'/'.$name,
			'+10 minutes'
		);
		return $response;
	}

	/**
	 * @param BucketName $bucket
	 */
	public function setBucket($bucket)
	{
		$this->bucket = $bucket;
		return $this;
	}

	/**
	 * @param folderName $folder
	 */
	public function setFolder($folder)
	{
		$this->folder = $folder;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getBucket()
	{
		if(!is_null($this->bucket)){
			$s3 = \App::make('aws')->createClient('s3');
			if($s3->doesBucketExist($this->bucket)){
				return $this->bucket;
			}
		}
		if (app()->environment() == 'staging') {
			return self::STAGING_BUCKET;
		}
		return self::PRODUCTION_BUCKET;
	}

	/**
	 * @return string
	 */
	public function getFolder()
	{
		if(!is_null($this->folder)){
			return $this->folder;
		}
		if (app()->environment() == 'staging') {
			return self::STAGING_FOLDER;
		}
		elseif (app()->environment() == 'production') {
			return self::PRODUCTION_FOLDER;
		}
		return self::LOCAL_FOLDER;
	}

	/**
	 * @param  fileName $fileName
	 * @return string
	 */
	public function getKey($fileName)
	{
		return $this->getFolder().'/'.date('FY').'/'.$fileName;
	}

}
