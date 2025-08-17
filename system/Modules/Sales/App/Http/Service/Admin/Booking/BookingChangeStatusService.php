<?php

namespace Modules\Sales\App\Http\Service\Admin\Booking;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Modules\Sales\App\Models\Booking;
use Modules\Shared\App\Events\AdminUserActivityLogEvent;
use Modules\Shared\Constant\ActivityTypeConstant;
use Modules\SystemConfiguration\App\Models\EmailTemplate;
use Modules\User\App\Events\SendPasswordResetLinkMail;
use Modules\User\App\Events\SendTripCompletedMail;
use Modules\User\App\Models\User;
use Modules\User\DTO\UserForgotPasswordDTO;
use Modules\User\DTO\UserTripCompletedDTO;

class BookingChangeStatusService
{
    public function changeStatus($data, string $ipAddress)
    {
        $this->validateStatus($data['status']);

        try {
            DB::beginTransaction();

            $booking = Booking::findOrFail($data['id']);
            $oldStatus = $booking->status;

            $booking->update(['status' => $data['status']]);

            DB::commit();

            $user = $booking->user;

            if ($data['status'] === 'completed') {
                $this->sendEmailOnTripCompleted($user);
            }

            $this->logStatusChange($booking, $oldStatus, $data['status'], $ipAddress);

        } catch (\Exception $e) {

            DB::rollBack();
            throw $e;
        }
    }

    protected function validateStatus(string $status)
    {
        $validStatuses = Booking::$status;

        if (!in_array($status, $validStatuses)) {
            throw ValidationException::withMessages([
                'status' => "Invalid status: $status. Valid statuses are: " . implode(', ', $validStatuses),
            ]);
        }
    }

    protected function logStatusChange(Booking $booking, string $oldStatus, string $newStatus, string $ipAddress)
    {
        Event::dispatch(new AdminUserActivityLogEvent(
            "{$booking->product_name} booking status changed from {$oldStatus} to {$newStatus} by: " . Auth::user()->name,
            Auth::id(),
            ActivityTypeConstant::BOOKING_STATUS_CHANGED,
            $ipAddress
        ));
    }

    private function sendEmailOnTripCompleted(User $user)
    {
        $template = EmailTemplate::where('name', 'trip_completed')->first();

        $message = strtr($template->message, [
            '{FULLNAME}' => $user->full_name,
        ]);

        $userForgotPasswordDTO = UserTripCompletedDTO::from([
            'title' => $template->title,
            'subject' => $template->subject,
            'description' => $message,
            'email' => $user->email,
        ]);

        Event::dispatch(new SendTripCompletedMail($userForgotPasswordDTO));
    }

}
