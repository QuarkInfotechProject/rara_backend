<?php

namespace Modules\User\Service;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Modules\Shared\Exception\Exception;
use Modules\Shared\StatusCode\ErrorCode;
use Modules\SystemConfiguration\App\Models\EmailTemplate;
use Modules\User\App\Events\SendPasswordResetLinkMail;
use Modules\User\App\Models\User;
use Modules\User\DTO\UserForgotPasswordDTO;

class UserForgotPasswordService
{
    function resetPassword($request)
    {
        $validateEmail = $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $validateEmail['email'])->first();

        if (!$user) {
            throw new Exception('Email not found.', ErrorCode::NOT_FOUND);
        }

        $token = Str::random(40);
        $createdAt = now();

        $existingToken = DB::table('password_reset_tokens')
            ->where('email', $validateEmail['email'])
            ->first();

        try {
            DB::beginTransaction();

            if ($existingToken) {
                DB::table('password_reset_tokens')
                    ->where('email', $validateEmail['email'])
                    ->update([
                        'token' => $token,
                        'created_at' => $createdAt,
                    ]);
            } else {
                DB::table('password_reset_tokens')->insert([
                    'email' => $validateEmail['email'],
                    'token' => $token,
                    'created_at' => $createdAt,
                ]);
            }

            $this->sendPasswordResetEmail($user, $validateEmail['email'], $token);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function sendPasswordResetEmail(User $user, $email, $token)
    {
        $template = EmailTemplate::where('name', 'end_user_forgot_password')->first();

        $message = strtr($template->message, [
            '{FULLNAME}' => $user['full_name'],
        ]);

        $userForgotPasswordDTO = UserForgotPasswordDTO::from([
            'title' => $template->title,
            'subject' => $template->subject,
            'description' => $message,
            'email' => $email,
            'token' => $token
        ]);

        Event::dispatch(new SendPasswordResetLinkMail($userForgotPasswordDTO));
    }
}
