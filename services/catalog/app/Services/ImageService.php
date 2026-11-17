<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Exception;

class ImageService
{
    public function uploadImage(Model $model, UploadedFile $file): string
    {
        $entityType = strtolower(class_basename($model));

        $response = Http::timeout(20)->attach(
            'img',
            file_get_contents($file->getRealPath()),
            $file->getClientOriginalName()
        )->post('http://image_processor:8080/process', [
            'entity_type' => $entityType,
        ]);

        if ($response->failed()) {
            throw new Exception("Ошибка обработки картинки: ", $response->json('error'));
        }

        return $response->json('filename');
    }
}
