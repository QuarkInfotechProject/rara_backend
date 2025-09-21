<?php

namespace Modules\Product\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\App\Models\User;

class ProductRatingReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'email',
        'cleanliness',
        'hospitality',
        'value_for_money',
        'communication',
        'overall_rating',
        'public_review',
        'private_review',
        'reply_to_public_review',
        'approved'
    ];

    protected $casts = [
        'cleanliness' => 'decimal:2',
        'hospitality' => 'decimal:2',
        'value_for_money' => 'decimal:2',
        'communication' => 'decimal:2',
        'overall_rating' => 'decimal:2',
        'approved' => 'boolean',
    ];

    /**
     * Get the product that owns the review.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user that wrote the review (if authenticated).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the review is from a guest user.
     */
    public function isGuestReview(): bool
    {
        return is_null($this->user_id);
    }

    /**
     * Get the reviewer's name (from user or email).
     */
    public function getReviewerNameAttribute(): string
    {
        if ($this->user) {
            return $this->user->name;
        }

        // For guest users, extract name from email or return 'Guest'
        if ($this->email) {
            $emailParts = explode('@', $this->email);
            return ucfirst($emailParts[0]);
        }

        return 'Guest';
    }

    /**
     * Get the reviewer's email.
     */
    public function getReviewerEmailAttribute(): string
    {
        return $this->email ?? ($this->user->email ?? '');
    }

    /**
     * Scope to get only approved reviews.
     */
    public function scopeApproved($query)
    {
        return $query->where('approved', true);
    }

    /**
     * Scope to get only guest reviews.
     */
    public function scopeGuestReviews($query)
    {
        return $query->whereNull('user_id');
    }

    /**
     * Scope to get only authenticated user reviews.
     */
    public function scopeUserReviews($query)
    {
        return $query->whereNotNull('user_id');
    }
}
