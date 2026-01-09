<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NFT extends Model
{
    use HasFactory;

    protected $table = 'nfts';

    protected $fillable = [
        'user_id',
        'token_id',
        'name',
        'description',
        'token_uri',
        'image_url',
        'contract_address',
        'collection_name',
        'metadata',
        'status',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Get the user who owns/created this NFT
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all listings for this NFT
     */
    public function listings(): HasMany
    {
        return $this->hasMany(NFTListing::class);
    }

    /**
     * Get the active listing for this NFT
     */
    public function activeListing()
    {
        return $this->hasOne(NFTListing::class)->where('status', 'active');
    }

    /**
     * Get all transactions for this NFT
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(NFTTransaction::class);
    }
}
