<?php

namespace Modules\Product\App\Service\Admin\Dashboard;

use Illuminate\Support\Facades\DB;

class GetProductRatingStatsService
{

    public function getReviewStats(): array
    {
        $basicStats = DB::table('product_rating_reviews as prr')
            ->select([
                // Total counts
                DB::raw('COUNT(*) as total_reviews'),
                DB::raw('SUM(CASE WHEN approved = 0 THEN 1 ELSE 0 END) as pending_approvals'),
                DB::raw('SUM(CASE WHEN reply_to_public_review IS NULL AND public_review IS NOT NULL THEN 1 ELSE 0 END) as pending_replies'),

                // Average ratings
                DB::raw('ROUND(AVG(cleanliness), 2) as avg_cleanliness'),
                DB::raw('ROUND(AVG(hospitality), 2) as avg_hospitality'),
                DB::raw('ROUND(AVG(value_for_money), 2) as avg_value_for_money'),
                DB::raw('ROUND(AVG(communication), 2) as avg_communication'),
                DB::raw('ROUND(AVG(overall_rating), 2) as avg_overall')
            ])
            ->first();

        $recentReviews = DB::table('product_rating_reviews as prr')
            ->join('users as u', 'prr.user_id', '=', 'u.id')
            ->join('products as p', 'prr.product_id', '=', 'p.id')
            ->select([
                'prr.id',
                'prr.product_id',
                'p.name as product_name',
                'prr.public_review',
                'prr.private_review',
                'prr.reply_to_public_review',
                'prr.approved',
                'prr.created_at',
                DB::raw('ROUND((
                    COALESCE(cleanliness, 0) +
                    COALESCE(hospitality, 0) +
                    COALESCE(value_for_money, 0) +
                    COALESCE(communication, 0) +
                    COALESCE(overall_rating, 0)
                ) / 5, 2) as average_rating')
            ])
            ->whereNotNull('public_review')
            ->orWhereNotNull('private_review')
            ->orderByDesc('prr.created_at')
            ->limit(20)
            ->get();

        return [
            'summary' => [
                'total_reviews' => $basicStats->total_reviews,
                'pending_approvals' => $basicStats->pending_approvals,
                'pending_replies' => $basicStats->pending_replies,
                'reply_completion_rate' => $basicStats->total_reviews > 0
                    ? round(100 - (($basicStats->pending_replies / $basicStats->total_reviews) * 100), 2)
                    : 0,
                'approval_completion_rate' => $basicStats->total_reviews > 0
                    ? round(100 - (($basicStats->pending_approvals / $basicStats->total_reviews) * 100), 2)
                    : 0
            ],
            'average_ratings' => [
                'cleanliness' => $basicStats->avg_cleanliness ?? 0,
                'hospitality' => $basicStats->avg_hospitality ?? 0,
                'value_for_money' => $basicStats->avg_value_for_money ?? 0,
                'communication' => $basicStats->avg_communication ?? 0,
                'overall' => $basicStats->avg_overall ?? 0
            ],
            'recent_reviews' => $recentReviews->map(function($review) {
                return [
                    'id' => $review->id,
                    'product' => [
                        'id' => $review->product_id,
                        'name' => $review->product_name
                    ],
                    'average_rating' => $review->average_rating,
                    'public_review' => $review->public_review,
                    'private_review' => $review->private_review,
                    'has_reply' => !is_null($review->reply_to_public_review),
                    'is_approved' => $review->approved,
                    'created_at' => $review->created_at
                ];
            })
        ];
    }

}
