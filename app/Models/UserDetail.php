<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone_number',
        'school',
        'grade',
        'address',
        'province_id',
        'city_id',
        'district_id',
        'village_id',
        'postal_code',
    ];

    /**
     * Get the user that owns the user detail.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
