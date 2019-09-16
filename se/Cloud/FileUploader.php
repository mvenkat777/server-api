<?php
namespace Platform\Cloud;

use Platform\Cloud\UploadHandler;
use App;
use Exception;
use Platform\Cloud\Transformers\FileTransformer;

class FileUploader {

	public function upload($file, $isPublic = false, $description = NULL, $groupId = NULL, $bucket='sourceasy-public', $folder='testing')
	{
		$uploadHandler = new UploadHandler();
		$name = $file[0]->getClientOriginalName();
		$extension = $file[0]->getClientOriginalExtension();
		$title = str_replace('.'.$extension, '', $name);
		$name = date("DMjGisY")."".rand(1000,9999).".".$extension;
		$mimeType = $file[0]->getMimeType();
		$webLink = $uploadHandler->generateUrl($name);
		$filePath = $file[0]->getRealPath();
		$actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

		$fileObject = '';
		
		//ADD THE PATH ACCORDING TO THE URL IN BUCKET - PRODUCTION AND TESTING FOLDER
		//IF URL IS HTTPS://WWW.SOURCEEASY.COM/----MOVE CONTENT TO PRODUCTION FOLDER
		//ELSE TESTING FOLDER
		if($_SERVER['HTTP_HOST'] == 'WWW.SOURCEEASY.COM' || $_SERVER['HTTP_HOST'] == 'www.sourceeasy.com' || $_SERVER['HTTP_HOST'] == 'cms.sourc.in')
		{
			// try{
				$s3 = \App::make('aws')->get('s3');
				$response = $s3->putObject(array(
				    'Bucket'     => $bucket,
				    'Key'        => $folder.'/'.date('FY').'/'.$name,
				    'SourceFile' => $filePath,
				    'ACL' => 'public-read'
				  ));
				$selfLink = $s3->getObjectUrl(getenv('AWS_BUCKET'), getenv('AWS_FOLDER_PRODUCTION').'/'.date('FY').'/'.$name);
				
				$result = $uploadHandler->setDatabase($name, $selfLink, $webLink, $isPublic, $mimeType, $extension, $description, $title);
				if($result == 'fail')
				{
					// try
					// {
   						$response = $s3->deleteMatchingObjects(getenv('AWS_BUCKET'), getenv('AWS_FOLDER_PRODUCTION').'/'.date('FY').'/'.$name);
				        return \Response::json([
			                'error' => [
			                    'message' => "Upload failed due to database",
			                    'status_code' => 400
			                ]
			            ], 400);
				    // } 
				    // catch (Exception $e) 
				    // {
					    // return \Response::json([
				     //            'error' => [
				     //                'message' => "Internal Error",
				     //                'status_code' => 500
				     //            ]
				     //        ], 500);
				    // }
				}
				$fileObject = $result;
			// }
			// catch(Exception $exception){
				// return \Response::json([
		  //               'error' => [
		  //                   'message' => "Upload failed",
		  //                   'status_code' => 400
		  //               ]
		  //           ], 400);	
			// }
			return (new FileTransformer)->transform($fileObject);
		}
		else{
			// try{
				$s3 = \App::make('aws')->createClient('s3');
				$response = $s3->putObject(array(
				    'Bucket'     => $bucket,
				    'Key'        => $folder.'/'.date('FY').'/'.$name,
				    'ContentType' => $mimeType,
				    'SourceFile' => $filePath,
				    'ACL' => 'public-read'
				  ));
				$selfLink = $s3->getObjectUrl(getenv('AWS_BUCKET'), getenv('AWS_FOLDER_TESTING').'/'.date('FY').'/'.$name);
				// return '<img src="'.$selfLink.'" alt="hhhh">';				
				$result = $uploadHandler->setDatabase($name, $selfLink, $webLink, $isPublic, $mimeType, $extension, $description, $title);
				if($result == 'fail')
				{
					//try
					//{
   						$response = $s3->deleteMatchingObjects(getenv('AWS_BUCKET'), getenv('AWS_FOLDER_TESTING').'/'.date('FY').'/'.$name);
				        return \Response::json([
			                'error' => [
			                    'message' => "Upload failed due to Database",
			                    'status_code' => 400
			                ]
			            ], 400);
				    //} 
				    //catch (Exception $e) 
				    //{
				      // return \Response::json([
			       //          'error' => [
			       //              'message' => "Internal Error",
			       //              'status_code' => 500
			       //          ]
			       //      ], 500);
				    //}
				}
				$fileObject = $result;
			//}
			//catch(Exception $exception){
				// return \Response::json([
		  //               'error' => [
		  //                   'message' => "Upload failed",
		  //                   'status_code' => 400
		  //               ]
		  //           ], 400);
			//}
		// return  $fileObject;
		return (new FileTransformer)->transform($fileObject);

		}
	}

	public function checkFile($name){

		$s3 = App::make('aws')->get('s3');
		if($_SERVER['HTTP_HOST'] == 'WWW.SOURCEEASY.COM' || $_SERVER['HTTP_HOST'] == 'www.sourceeasy.com' || $_SERVER['HTTP_HOST'] == 'cms.sourc.in')
		{
			//try{
				$response = $s3->getObject([
				    'Bucket' => getenv('AWS_BUCKET'),
				    'Key'    => getenv('AWS_FOLDER_PRODUCTION').'/'.date('FY').'/'.$name
				]);
			return true;
			//}
			//catch(Exception $exception){

			//return false;
			//}
		}
		else{
			//try{

				$response = $s3->getObject([
				    'Bucket' => getenv('AWS_BUCKET'),
				    'Key'    => getenv('AWS_FOLDER_TESTING').'/'.date('FY').'/'.$name
				]);
			return true;
			//}
			//catch(Exception $exception){

			//return false;
			//}
		}
	}
}
