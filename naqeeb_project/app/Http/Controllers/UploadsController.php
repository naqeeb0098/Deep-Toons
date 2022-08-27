<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use Illuminate\Http\Request;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Handler\DropZoneUploadHandler;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;

class UploadsController extends Controller
{
    public function uploadFiles(Request $request)
    {
        $receiver = new FileReceiver('files', $request, HandlerFactory::classFromRequest($request, DropZoneUploadHandler::class));
        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }

        $fileReceived = $receiver->receive();
        if ($fileReceived->isFinished()) {
            $file = $fileReceived->getFile();
            $extension = $file->getClientOriginalExtension(); //get original file name
            $fileName = str_replace('.'.$extension, '', $file->getClientOriginalName());
            $fileName .= '_' . md5(time()) . '.' . $extension;

            $size = $file->getSize();
            $mime = str_replace('/', '-', $file->getMimeType());
            $type = $file->getType();
            $file_path = $request->type;
            $path = public_path().'/'.$file_path;
            $file->move($path, $fileName);
            $path = $file_path.'/'.$fileName;

            // $upload_file = new Upload(); 
            // $upload_file->type = $request->type;
            // $upload_file->path = $path;
            // $upload_file->save();
            return response()->json([
                'path' => $path,
                'name' => $fileName,
                'type' => $type
            ]);
        }
        
        $handler = $fileReceived->handler();
        return [
            'done' => $handler->getPercentageDone()
        ];
    }
}
