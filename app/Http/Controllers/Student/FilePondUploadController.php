<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use Image;
use App\Models\EduFilePond_Global;
use App\Http\Controllers\Controller;

class FilePondUploadController extends Controller
{
    public function filepondUpload(Request $request)
    {
        $mainFiles = $request->attachment;

        if($request->hasFile('attachment')){ 
         
            $mainFiles = $request->file('attachment');  
    
            foreach ($mainFiles as $mainFile) {
                $validPath = 'uploads/filepond';
                $fileExtention = $mainFile->extension();
                $fileOriginalName = $mainFile->getClientOriginalName();
                $file_size 	= $mainFile->getSize();
    
                $validExtentions = array('jpeg', 'jpg', 'png', 'gif');
                $path = public_path($validPath);
                $currentTime = time();
                $fileName = $currentTime.'.'.$fileExtention;
                if (in_array($fileExtention, $validExtentions)) {
                    $mainFile->move($path, $fileName);
                    //create instance
                    $img = Image::make($path.'/'.$fileName);
                    //resize image
                    $img->resize(80, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $img->save($path.'/thumb/'.$fileName);
    
                    EduFilePond_Global::create([
                        'file_name'          => $fileName,
                        'folder_name'        => $validPath,
                        'file_original_name' => $fileOriginalName,
                        'size'               => $file_size,
                        'extention'          => $fileExtention
                    ]);
                    $output['file_name'] = $fileName;
                    $output['msg_type'] = 'success';
    
                    return $output;
                } else {
                    return '';
                }
            }
            return '';
        } 
    }

    public function filepondDelete(Request $request)
    {
        $temporaryUploadedFile = EduFilePond_Global::valid()->where('file_name', $request->attachment)->first();
        if ($temporaryUploadedFile) {
            File::delete(public_path('uploads/filepond/'.$temporaryUploadedFile->file_name));
            File::delete(public_path('uploads/filepond/thumb/'.$temporaryUploadedFile->file_name));
                                
            DB::table('edu_file_ponds')->where('file_name', $temporaryUploadedFile->file_name)->delete();

            return 'Deleted';
        } else {
            return '';
        }
    }

}
