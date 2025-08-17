<?php

namespace Modules\Media\Service\File;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Modules\Media\App\Models\File;
use Modules\Shared\App\Events\AdminUserActivityLogEvent;
use Modules\Shared\Constant\ActivityTypeConstant;
use Modules\Shared\Exception\Exception;
use Modules\Shared\ImageUpload\Service\ImageUploadService;
use Modules\Shared\StatusCode\ErrorCode;

class FileDestroyService
{
    function __construct(private ImageUploadService $imageUploadService)
    {
    }

    function destroy(int $id, string $ipAddress)
    {
        $file = File::find($id);

        if (!$file) {
            throw new Exception('File not found.', ErrorCode::NOT_FOUND);
        }

        $this->imageUploadService->remove($file->temp_filename, 'modules/files/');
        $this->imageUploadService->remove($file->temp_filename, 'modules/files/Thumbnail/');

        Event::dispatch(new AdminUserActivityLogEvent(
            "{$file->filename} has been destroyed by: " . Auth::user()->name,
            Auth::id(),
            ActivityTypeConstant::FILE_DESTROYED,
            $ipAddress
        ));

        $file->delete();
    }
}
