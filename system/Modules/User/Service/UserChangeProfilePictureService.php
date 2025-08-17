<?php

namespace Modules\User\Service;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Modules\Media\App\Models\File;
use Modules\Shared\App\Events\AdminUserActivityLogEvent;
use Modules\Shared\Constant\ActivityTypeConstant;
use Modules\Shared\ImageUpload\Service\ImageUploadService;
use Modules\User\App\Models\User;

class UserChangeProfilePictureService
{
    private $imageUploadService;

    public function __construct(ImageUploadService $imageUploadService)
    {
        $this->imageUploadService = $imageUploadService;
    }

    public function changeProfilePicture($request, string $ipAddress)
    {
        $file = $request->file('profilePicture');
        $user = Auth::user();

        try {
            DB::beginTransaction();

            $fileName = $this->uploadProfilePicture($file);
            $this->updateUserProfilePicture($user, $fileName);

            DB::commit();

            return $fileName;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function uploadProfilePicture($file)
    {
        $destinationPath = public_path('modules/files');
        return $this->imageUploadService->upload($file, $destinationPath);
    }

    private function updateUserProfilePicture(User $user, string $fileName)
    {
        // Delete old profile picture if exists
        if ($user->profile_picture) {
            $this->imageUploadService->remove($user->profile_picture, 'modules/files/');
        }

        // Update user's profile picture
        $user->profile_picture = $fileName;
        $user->save();
    }
}
