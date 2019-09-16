<?php

namespace Platform\Cloud;

use App\Upload;
use App\User;
use Exception;

class UploadHandler
{
    private $count = 0;
    private $length = 60;

    public function setDatabase($name, $selfLink, $webLink, $isPublic, $mimeType, $extension, $description, $title)
    {
        $upload = new Upload();
        $upload->name = $name;
        $upload->self_link = $selfLink;
        $upload->web_link = $selfLink;// Same as selfLink as in api.v2
        $upload->is_public = $isPublic;
        $upload->mime_type = $mimeType;
        $upload->extension = $extension;
        $upload->description = $description;
        $upload->title = $title;

        if ($upload->save()) {
            return $upload;
        } else {
            return 'fail';
        }
    }

    public function generateUrl($name)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $link = substr(str_shuffle($characters), 0, $this->length);
        $upload = Upload::where('web_link', '=', $link)->get();
        if (count($upload) > 0) {
            if ($this->count > 10) {
                $this->generateUrl($name);
                $this->count = 0;
                ++$this->lengt;
            }
            ++$this->count;
            $this->generateUrl($name);
        } else {
            return $link;
        }

        return $link;
    }

    public function checkLink($link, $isDownload = false)
    {
        $dbFile = Upload::where('web_link', '=', $link)->first();
        if (count($dbFile) < 1) {
            return [false,'404 Not Found'];
        }

        if ($dbFile->isPublic == 1) {
            if ($isDownload) {
                return [true, $dbFile];
            } else {
                return [true, '<meta name="viewport" content="width=device-width, minimum-scale=0.1"><style>body{margin:0px;}</style><img src="'.$dbFile->self_link.'" alt="'.$dbFile->name.'" style="-webkit-user-select: none; max-height:100%; max-width:80%">'];
            }
        }

        return [true, $dbFile];
    }

    public function setIsPublic($fileId, $isPublic)
    {
        try {
            $upload = Upload::where('id', '=', $fileId)->update(array('is_public' => $isPublic));
        } catch (Exception $exception) {
            return \Response::json([
                'error' => [
                    'message' => 'Upload Failed',
                    'status_code' => 500,
                ],
            ], 500);
        }

        return 'success';
    }

    public function getPublicFiles()
    {
        $files = Upload::where('is_public', '=', true)
                        ->orderBy('created_at', 'desc')
                        ->paginate(100);

        return $files;
    }

    public function getSharedFiles()
    {
        $files = Upload::where('is_public', '=', true)
                        ->orderBy('created_at', 'desc')
                        ->paginate(100);

        return $files;
    }

    public function getAllFiles()
    {
        $files = Upload::orderBy('created_at', 'desc')
                ->get();

        return $files;
    }

    public function deleteFile($fileId)
    {
        try {
            $fileDelete = Upload::find($fileId);

            $fileDelete->delete();
            if ($_SERVER['HTTP_HOST'] == 'WWW.SOURCEEASY.COM' || $_SERVER['HTTP_HOST'] == 'www.sourceeasy.com' || $_SERVER['HTTP_HOST'] == 'cms.sourc.in') {
                try {
                    $name = $fileDelete->name;

                    $s3 = \App::make('aws')->createClient('s3');

                    $response = $s3->deleteMatchingObjects(getenv('AWS_BUCKET'), getenv('AWS_FOLDER_PRODUCTION').'/'.date('FY').'/'.$name);

                    return 'Success Deletion';
                } catch (Exception $e) {
                    return "There was an error.\n";
                }
            } else {
                try {
                    $name = $fileDelete->name;

                    $s3 = \App::make('aws')->createClient('s3');

                    $response = $s3->deleteMatchingObjects(getenv('AWS_BUCKET'), getenv('AWS_FOLDER_TESTING').'/'.date('FY').'/'.$name);

                    return 'Success Deletion';
                } catch (Exception $e) {
                    return "There was an error.\n";
                }
            }
        } catch (Exception $e) {
            return 'Deletion failed.File Not Found';
        }
    }

    public function getEditorSharedLink()
    {
        $files = Upload::where('is_public', '=', true)
                        ->orderBy('created_at', 'desc')
                        ->select('self_link as image')
                        ->get();

        return $files;
    }
}
