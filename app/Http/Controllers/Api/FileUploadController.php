<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Upload\StoreUploadRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class FileUploadController extends Controller
{
    public function store(StoreUploadRequest $request): JsonResponse
    {
        $file = $request->file('file');
        $disk = Storage::disk('public');
        $basePath = 'uploads';

        $extension = strtolower($file->getClientOriginalExtension());
        $filename = Str::uuid()->toString();

        if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true)) {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file->getPathname());
            $image = $image->scale(width: 1600);

            $storedPath = sprintf('%s/%s.jpg', $basePath, $filename);
            $disk->put($storedPath, (string) $image->toJpeg(75));
            $mime = 'image/jpeg';
        } else {
            $storedPath = sprintf('%s/%s.%s', $basePath, $filename, $extension);
            $disk->putFileAs($basePath, $file, $filename . '.' . $extension);
            $mime = $file->getClientMimeType();
        }

        return response()->json([
            'url' => $disk->url($storedPath),
            'path' => $storedPath,
            'mime' => $mime,
            'size' => $disk->size($storedPath),
        ], 201);
    }
}
