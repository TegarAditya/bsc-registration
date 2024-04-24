<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['province_id', 'code', 'name'];

    /**
     * Get the province that the city belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Get the districts associated with the city.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function districts()
    {
        return $this->hasMany(District::class);
    }

    /**
     * Get the villages associated with the city.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function villages()
    {
        return $this->hasManyThrough(Village::class, District::class);
    }

    /**
     * Get the users associated with the city.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function users()
    {
        return $this->hasManyThrough(UserDetail::class, User::class);
    }

    /**
     * Get the user details associated with the city.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userDetails()
    {
        return $this->hasMany(UserDetail::class);
    }
}
