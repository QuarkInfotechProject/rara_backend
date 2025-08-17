<?php

namespace Modules\PageVault\App\Service\PageVault;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Modules\PageVault\App\Models\PageVault;
use Modules\Shared\App\Events\AdminUserActivityLogEvent;
use Modules\Shared\Constant\ActivityTypeConstant;

class UpdatePageVaultService
{

    public function updatePageVault($data, $ipAddress)
    {
        try {
            DB::beginTransaction();

            $validatedData = Validator::make($data, [
                'title' => 'required|string|max:255',
                'type' => 'required',
                'header' => 'required|string|max:255',
                'content1' => 'nullable|string',
                'content2' => 'nullable|string',
                'content3' => 'nullable|string',
                'is_active' => 'required|in:1,0',
            ])->validate();

            $page = PageVault::where('type', $validatedData['type'])->firstOrFail();

            if (!$page) {
                throw new \Exception('Page Not found');
            }

            $page->update([
                'title' => $data['title'],
                'header' => $data['header'],
                'content1' => $data['content1'],
                'content2' => $data['content2'],
                'content3' => $data['content3'],
                'is_active' => $data['is_active'],
            ]);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error updating page vault: ' . $exception->getMessage(), [
                'exception' => $exception,
                'data' => $data
            ]);
            throw $exception;
        }

        Event::dispatch(new AdminUserActivityLogEvent(
                "{$page->title} page has been updated by: " . Auth::user()->name,
                Auth::id(),
                ActivityTypeConstant::PAGE_UPDATED,
                $ipAddress)
        );
    }

}
