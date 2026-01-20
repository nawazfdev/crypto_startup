<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'creator_id',
        'requester_id',
        'type',
        'title',
        'description',
        'goal_amount',
        'current_amount',
        'price',
        'status',
        'message_id',
        'is_marketplace',
        'deadline',
    ];

    protected $casts = [
        'goal_amount' => 'decimal:2',
        'current_amount' => 'decimal:2',
        'price' => 'decimal:2',
        'is_marketplace' => 'boolean',
        'deadline' => 'datetime',
    ];

    // Request types
    const TYPE_PRIVATE = 'private';
    const TYPE_PUBLIC = 'public';
    const TYPE_MARKETPLACE = 'marketplace';

    // Statuses
    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Get the creator (user who will fulfill the request)
     */
    public function creator()
    {
        return $this->belongsTo(\App\User::class, 'creator_id');
    }

    /**
     * Get the requester (user who made the request)
     */
    public function requester()
    {
        return $this->belongsTo(\App\User::class, 'requester_id');
    }

    /**
     * Get the message (for private requests)
     */
    public function message()
    {
        return $this->belongsTo(\App\Model\UserMessage::class, 'message_id');
    }

    /**
     * Get all contributions for this request
     */
    public function contributions()
    {
        return $this->hasMany(CustomRequestContribution::class);
    }

    /**
     * Get completed contributions
     */
    public function completedContributions()
    {
        return $this->hasMany(CustomRequestContribution::class)->where('status', 'completed');
    }

    /**
     * Calculate progress percentage for marketplace requests
     */
    public function getProgressPercentageAttribute()
    {
        if ($this->goal_amount > 0) {
            return min(100, ($this->current_amount / $this->goal_amount) * 100);
        }
        return 0;
    }

    /**
     * Check if goal is reached
     */
    public function isGoalReached()
    {
        return $this->current_amount >= $this->goal_amount;
    }

    /**
     * Scope for marketplace requests
     */
    public function scopeMarketplace($query)
    {
        return $query->where('is_marketplace', true)->orWhere('type', self::TYPE_MARKETPLACE);
    }

    /**
     * Scope for public requests
     */
    public function scopePublic($query)
    {
        return $query->where('type', self::TYPE_PUBLIC);
    }

    /**
     * Scope for private requests
     */
    public function scopePrivate($query)
    {
        return $query->where('type', self::TYPE_PRIVATE);
    }
}
