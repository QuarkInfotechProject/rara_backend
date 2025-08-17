<?php

namespace Modules\Media\Service\File;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Modules\Media\App\Models\File;
use Modules\Shared\App\Events\AdminUserActivityLogEvent;
use Modules\Shared\Constant\ActivityTypeConstant;
use Modules\Shared\ImageUpload\Service\ImageUploadService;

class FileCreateService
{
    function __construct(private ImageUploadService $imageUploadService)
    {
    }

    function create($request, string $ipAddress)
    {
        $files = $request->file('files');

        try {
            DB::beginTransaction();

            foreach ($files as $file) {
                $this->createFile($file, $request->fileCategoryId, $ipAddress);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function createFile($file, $fileCategoryId, $ipAddress)
    {
        $size = $file->getSize();
        $fileName = $this->imageUploadService->upload($file, public_path('modules/files'));
        $filePath = public_path('modules/files') . '/' . $fileName;

        [$width, $height] = getimagesize($filePath);

        $fileData = [
            'file_category_id' => $fileCategoryId,
            'is_grouped' => (bool)$fileCategoryId,
            'filename' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            'temp_filename' => $fileName,
            'disk' => config('filesystems.default'),
            'path' => url('modules/files'),
            'extension' => $file->guessClientExtension() ?? '',
            'mime' => $file->getClientMimeType(),
            'size' => $size,
            'width' => $width,
            'height' => $height,
        ];
        Log::info(json_encode($fileData));

        File::create($fileData);

        $this->logFileCreatedEvent($fileData['filename'], $ipAddress);
    }

    private function logFileCreatedEvent($filename, $ipAddress)
    {
        Event::dispatch(new AdminUserActivityLogEvent(
            "$filename has been created by: " . Auth::user()->name,
            Auth::id(),
            ActivityTypeConstant::FILE_CREATED,
            $ipAddress
        ));
    }
}
