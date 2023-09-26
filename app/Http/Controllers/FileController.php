<?php

namespace App\Http\Controllers;

use App\Models\File as AppFile;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
    private $_imageMime = 'image';

    private $_docMime = 'mimetypes:application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf';

    private $_fileSize = 2048;

    private static $mime2ext = [
        'image/bmp' => 'bmp',
        'image/x-bmp' => 'bmp',
        'image/x-bitmap' => 'bmp',
        'image/x-xbitmap' => 'bmp',
        'image/x-win-bitmap' => 'bmp',
        'image/x-windows-bmp' => 'bmp',
        'image/ms-bmp' => 'bmp',
        'image/x-ms-bmp' => 'bmp',
        'image/cdr' => 'cdr',
        'image/x-cdr' => 'cdr',
        'image/gif' => 'gif',
        'image/x-icon' => 'ico',
        'image/x-ico' => 'ico',
        'image/vnd.microsoft.icon' => 'ico',
        'image/jp2' => 'jp2',
        'image/jpx' => 'jp2',
        'image/jpm' => 'jp2',
        'image/jpeg' => 'jpg',
        'image/pjpeg' => 'jpg',
        'image/png' => 'png',
        'image/x-png' => 'png',
        'application/pdf' => 'pdf',
        'image/vnd.adobe.photoshop' => 'psd',
        'image/svg+xml' => 'svg',
        'image/tiff' => 'tiff',
        'image/webp' => 'webp',
    ];

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function upload_cover(Request $request)
    {
        return $this->upload_file(
            $request,
            AppFile::FILE_IMAGE,
            'coverImage',
            'farmers-library/cover'
        );
    }

    public function uploadCoverImage(Request $request)
    {
        return $this->upload_file(
            $request,
            AppFile::FILE_IMAGE,
            'coverImage',
            'farmers-library/cover'
        );
    }

    public function uploadInstallerProjectImage(Request $request)
    {
        return $this->upload_file(
            $request,
            AppFile::FILE_IMAGE,
            'gallery',
            'upload/installer-projects'
        );
    }

    public function uploadCompanyLogoImage(Request $request)
    {
        return $this->upload_file(
            $request,
            AppFile::FILE_IMAGE,
            'company_logo',
            'upload/company-logo'
        );
    }

    public function uploadDocument(Request $request)
    {
        return $this->upload_file(
            $request,
            AppFile::FILE_DOC,
            'document',
            'upload/documents'
        );
    }

    public function uploadMaterialImage(Request $request)
    {
        return $this->upload_file(
            $request,
            AppFile::FILE_IMAGE,
            'image',
            'materials'
        );
    }

    public function uploadPostImage(Request $request)
    {
        return $this->upload_file(
            $request,
            AppFile::FILE_IMAGE,
            'image',
            'upload/blog-images'
        );
    }

    public function removeCoverImage(Request $request)
    {
        return $this->delete_file(
            $request,
            'farmers-library/cover'
        );
    }

    private function remove_file(Request $request, array $delete)
    {
        $val = Validator::make(['deleteImageId' => $delete[0]], [
            'deleteImageId' => 'required|exists:files,id',
        ]);

        $data = [];
        if ($val->fails()) {
            $data['success'] = 0;
            $data['error'] = $val->errors()->first('deleteImageId'); // Error response
        } else {
            $file = AppFile::where('id', $delete[0])->first();
            if ($file && $file->path === $delete[1]) {
                $path = "/storage/{$file->path}/{$file->filename}";
                if (file_exists(public_path() . $path)) {
                    unlink(public_path() . $path);
                }
                $file->delete();
                $data['success'] = 1;
                $data['removed_id'] = $delete[0];
                $data['error'] = 'File Removed.'; // Error response
            } else {
                //its not there so mission accomplished
                $data['success'] = 1;
                $data['error'] = 'File removed already.'; // Error response
            }
        }

        return redirect()->back()->with('flash', ['file-removed' => $data]);
    }

    private function upload_file(Request $request, int $type, string $fieldName, string $storagePath)
    {
        $data = [];

        //Validate File
        $mimes = $type === AppFile::FILE_DOC ? $this->_docMime : $this->_imageMime;
        $val = Validator::make($request->all(), [
            $fieldName => "required|{$mimes}|max:{$this->_fileSize}",
        ]);

        //if validation fails return error
        if ($val->fails()) {
            $data['success'] = 0;
            $data['error'] = $val->errors()->first($fieldName); // Error response
        } else {
            //second attempt upload to upload (move on submit)
            $file = $request->file($fieldName);

            $filename = time() . '-' . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)
                . '.' . (self::$mime2ext[$file->getMimeType()] ?: 'tmp');
            $path = pathinfo($file->storePubliclyAs('upload', $filename, 'public'));

            //validation passed, attempt to store
            ////create model (moves file and sets parameters
            //            ($file = AppFile::makeFromRequestFile($request->file($fieldName), $type, $storagePath))
            //                ->save();

            //Todo : MYET-130 Optimise and cache sizes

            //return path / thumbnail

            $data = [
                $fieldName => [
                    'filename' => $path['basename'],
                    'storage' => "{$path['dirname']}/{$path['basename']}",
                    'url' => "/storage/{$path['dirname']}/{$path['basename']}",
                ],
            ];
        }

        return redirect()->back()->with('flash', ['file-uploads' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //Validate File

        //load model

        //update model

        //save model (move file in model)

        //Todo : MYET-130 Optimise and cache sizes

        //return path / thumbnail
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
