<?php

namespace Gmf\Sys\Services;

use GAuth;
use Gmf\Sys\Builder;
use Gmf\Sys\Models;
use Uuid;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Storage;

class File
{
  private $request;
  public function __construct(Request $request = null)
  {
    $this->request = $request;
  }
  public function storage($inputName, $path = '', $disk = 'public')
  {
    $files = false;
    if (is_string($inputName) && $this->request) {
      if ($this->request->hasFile($inputName)) {
        $files = $this->request->file($inputName);
      } else if ($this->request->has($inputName)) {
        $files = json_decode(json_encode($this->request->input($inputName)));
      }
    } else {
      if (is_a($inputName, UploadedFile::class)) {
        $files = $inputName;
      } else {
        $files = json_decode(json_encode($inputName));
      }
    }
    if (!$files) {
      return false;
    }
    if (!is_array($files)) {
      $files = [$files];
    }
    $datas = [];
    foreach ($files as $key => $file) {
      $id = $this->storageItem($file, $path, $disk);
      if ($id) {
        $datas[] = $id;
      }
    }
    return count($datas) ? collect($datas) : false;
  }
  private function storageItem($file, $path = '', $disk = 'public')
  {
    if (is_a($file, UploadedFile::class)) {
      return $this->storageFile($file, $path, $disk);
    }
    if (!empty($file->title) && !empty($file->data)) {
      return $this->storageBase64($file, $path, $disk);
    }
    return false;
  }
  private function storageFile($file, $path = '', $disk = 'public')
  {
    $builder = new Builder;
    $builder->disk($disk);
    $builder->ent_id(GAuth::entId());
    $builder->user_id(GAuth::id());
    $builder->code(Uuid::generate());

    $builder->title($file->getClientOriginalName());

    $builder->size($file->getClientSize());

    $builder->type($file->getClientMimeType());

    $builder->ext($file->getClientOriginalExtension());

    if ($path) {
      $name = $path . '/' . date('Ymd', time());
    } else {
      $name = date('Ymd', time());
    }
    $name .= Uuid::generate();
    if ($builder->ext) {
      $name .= '.' . $builder->ext;
    }
    $builder->path($name);

    $url = $file->storeAs('', $builder->path, $builder->disk);
    $disk = Storage::disk($builder->disk);

    $contents = $disk->get($builder->path);
    $contents = base64_encode($contents);
    $contents = $builder->type . ';base64,' . $contents;

    $builder->url($disk->url($url));

    $fileItem = Models\File::create($builder->toArray());

		//Models\FileContent::create(['file_id' => $fileItem->id, 'data' => $contents]);

    return $fileItem;
  }
  private function storageBase64($file, $path = '', $disk = 'public')
  {
    $builder = new Builder;
    $builder->disk($disk);
    $builder->ent_id(GAuth::entId());
    $builder->user_id(GAuth::id());
    $builder->code(Uuid::generate());
    $builder->title($file->title);
    if (!empty($file->size)) {
      $builder->size($file->size);
    }
    if (!empty($file->type)) {
      $builder->type($file->type);
    }
    if (!empty($file->ext)) {
      $builder->ext($file->ext);
    }
    if ($path) {
      $name = $path . '/' . date('Ymd', time());
    } else {
      $name = date('Ymd', time());
    }
    $name .= Uuid::generate();
    if ($builder->ext) {
      $name .= '.' . $builder->ext;
    }
    $builder->path($name);

    if (preg_match('/^(data:)/', $file->data, $result)) {
      $base64_body = substr(strstr($file->data, ','), 1);
      $type = $builder->type;
      $fileCode = base64_decode($base64_body);
      $disk = Storage::disk($builder->disk);
      $bool = $disk->put($builder->path, $fileCode);
      if (!$bool) {
        return false;
      }
      $builder->url($disk->url($builder->path));
    }
    $fileItem = Models\File::create($builder->toArray());
    if (!empty($file->data)) {
			//Models\FileContent::create(['file_id' => $fileItem->id, 'data' => $file->data]);
    }
    return $fileItem;
  }
}
