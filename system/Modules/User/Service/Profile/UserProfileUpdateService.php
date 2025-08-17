<?php

namespace Modules\User\Service\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserProfileUpdateService
{

    function update($data)
    {
        $user = Auth::user();

        try {
            DB::beginTransaction();

            $user->update([
                'phone_no' => $data['phone_no'],
                'country' => $data['country'] ?? $data->country,
                'offers_notification' => $data['offers_notification'] ?? $user->offers_notification,
                'full_name' => $data['full_name'] ?? $user->full_name,
            ]);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
