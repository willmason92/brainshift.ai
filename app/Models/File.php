<?php

namespace App\Models;

use ESolution\DBEncryption\Encrypter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'path',
        'mimetype',
    ];

    protected $appends = [
        'url',
        'delete_key',
    ];

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
        'image/vnd.adobe.photoshop' => 'psd',
        'image/svg+xml' => 'svg',
        'image/tiff' => 'tiff',
        'image/webp' => 'webp',
    ];

    const FILE_IMAGE = 0;

    const FILE_DOC = 1;

    public static function uploadBase64($b64string)
    {
        $r = false;
        $b64image = preg_replace('/^data:image\/\w+;base64,/', '', $b64string);
        preg_match('/^data:image\/([^;]+);base64[^"]+/', $b64string, $b64type);

        if (! empty($b64image) && isset($b64type[1]) && ! empty($b64type[1])) {
            if (($ext = (self::$mime2ext['image/'.$b64type[1]] ?? null)) && ! empty($ext)) {
                $filepath = 'upload/tmp-shed-solution-'.time().".{$ext}";
                Storage::disk('public')->put($filepath, base64_decode($b64image));
                $r = $filepath;
            }
        }

        return $r;
    }

    public static function moveUploadedFile(string $filename, int $type, string $storagePath, string $newFilename = null)
    {
        $destiny = empty($newFilename) ? $filename : $newFilename;
        //dd('public/upload/'.$filename . " -=- " . 'public/'.$storagePath.'/'.$destiny);
        Storage::move('public/upload/'.$filename, 'public/'.$storagePath.'/'.$destiny);

        return new File([
            'filename' => $destiny,
            'path' => $storagePath,
            'mimetype' => $type === self::FILE_DOC ? self::FILE_DOC : self::FILE_IMAGE,
        ]);
    }

    public static function makeFromRequestFile(UploadedFile $file, int $type, string $storagePath)
    {
        $filename = time().'-'.pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)
            .'.'.(self::$mime2ext[$file->getMimeType()] ?: 'tmp');
        $path = pathinfo($file->storePubliclyAs($storagePath, $filename, 'public'));

        return new File([
            'filename' => $path['basename'],
            'path' => $path['dirname'],
            'mimetype' => $type === self::FILE_DOC ? self::FILE_DOC : self::FILE_IMAGE,
        ]);
    }

    public function url()
    {
        if (! empty($this->id)) {
            $path = "/storage/{$this->path}/{$this->filename}";
            if (file_exists(public_path().$path)) {
                return $path;
            }
        }

        return false;
    }

    public function getUrlAttribute()
    {
        return $this->url();
    }

    public function getDeleteKeyAttribute()
    {
        return Encrypter::encrypt(json_encode([$this->id, $this->path]));
    }

    /*
     *
$table->string('filename')->default(NULL);
$table->string('path');
$table->string('mimetype',50);
     */
}
