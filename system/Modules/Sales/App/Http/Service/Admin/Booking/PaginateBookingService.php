<?php

namespace Modules\Sales\App\Http\Service\Admin\Booking;

use Modules\Sales\App\Models\Booking;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class PaginateBookingService
{
    public function getBookingsList(array $filters, int $perPage = 30): LengthAwarePaginator
    {
        try {
            $today = now()->startOfDay();
            $yesterday = now()->subDay()->startOfDay();
            $tomorrow = now()->addDay()->startOfDay();

            $query = Booking::query()
                ->with('agent', 'product', 'additionalBookingProducts', 'user')
                ->when($filters['name'] ?? null, function (Builder $query, $name) {
                    $query->whereHas('user', function (Builder $query) use ($name) {
                        $query->where('name', 'like', "%{$name}%");
                    });
                })
                ->when($filters['email'] ?? null, function (Builder $query, $email) {
                    $query->where('email', $email);
                })

                ->when($filters['product_type'] ?? null, function (Builder $query, $email) {
                    $query->where('product_type', $email);
                })

                ->when($filters['fullname'] ?? null, function (Builder $query, $fullname) {
                    $query->where('fullname', 'like', "%{$fullname}%");
                })
                ->when($filters['status'] ?? null, function (Builder $query, $status) {
                    $query->where('status', $status);
                })
                ->when($filters['mobile_number'] ?? null, function (Builder $query, $mobile_number) {
                    $query->where('mobile_number', $mobile_number);
                })
                ->when(($filters['from_date'] ?? null) && ($filters['to_date'] ?? null), function (Builder $query) use ($filters) {
                    $query->whereBetween('from_date', [$filters['from_date'], $filters['to_date']]);
                })
                ->when($filters['agent'] ?? null, function (Builder $query, $agentId) {
                    $query->where('agent_id', $agentId);
                })
                ->when($filters['product_name'] ?? null, function (Builder $query, $productName) {
                    $query->whereHas('product', function (Builder $query) use ($productName) {
                        $query->where('name', 'like', "%{$productName}%");
                    });
                })
                ->orderByRaw(
                    "CASE
                    WHEN from_date = ? THEN 1
                    WHEN from_date = ? THEN 2
                    WHEN from_date = ? THEN 3
                    ELSE 4
                END", [$today, $yesterday, $tomorrow]
                )
                ->orderBy('from_date')
                ->orderBy('created_at', 'desc');

            $bookings = $query->paginate($perPage);

            $bookings->getCollection()->transform(function ($booking) {
                return [
                    'id' => $booking->id,
                    'fullname' => $booking->fullname,
                    'agent_name' => $booking->agent ? $booking->agent->firstname . ' ' . ($booking->agent->lastname ?? '') : null,
                    'product_name' => $booking->product->name ?? null,
                    'product_type' => $booking->product->type ?? null,
                    'type' => $booking->type,
                    'status' => $booking->status,
                    'from_date' => $booking->from_date,
                    'has_responded' => $booking->has_responded,
                    'to_date' => $booking->to_date,
                    'user' => $booking->user ? [
                        'name' => $booking->user->full_name,
                        'email' => $booking->user->email,
                    ] : null,
                    'additional_products' => $booking->additionalBookingProducts->map(function ($product) {
                        return [
                            'id' => $product->id,
                            'name' => $product->name,
                        ];
                    }),
                ];
            });

            return $bookings;

        } catch (\Exception $e) {
            throw $e;
        }
    }

}
