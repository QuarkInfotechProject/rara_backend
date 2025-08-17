<?php

namespace Modules\User\Service;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Shared\Exception\Exception;
use Modules\Shared\StatusCode\ErrorCode;
use Modules\SystemConfiguration\App\Models\SystemConfig;

class UserResetPasswordService
{
    function resetPassword($data)
    {
        $tokenFromLink = $data['token'];

        $tokenRecord = DB::table('password_reset_tokens')
            ->where('token', $tokenFromLink)
            ->first();

        if ($tokenRecord && !$this->tokenExpired($tokenRecord->created_at)) {
            $this->setPassword($data, $tokenRecord);
        } else {
            throw new Exception('Invalid or expired password reset link.', ErrorCode::GONE);
        }
    }

    private function tokenExpired($createdAt)
    {
        $tokenExpirationTime = SystemConfig::where('name', 'token_expiration_time')->value('value');

        $expiryTime = Carbon::parse($createdAt)->addMinutes($tokenExpirationTime);

        return now()->gt($expiryTime);
    }

    private function setPassword($data, $tokenRecord)
    {
        DB::table('users')
            ->where('email', $tokenRecord->email)
            ->update([
                'password' => bcrypt($data['confirmPassword']),
            ]);

        // Delete the used token
        DB::table('password_reset_tokens')
            ->where('token', $data['token'])
            ->delete();
    }
}
