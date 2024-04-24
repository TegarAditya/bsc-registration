<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Province extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['code', 'name'];

    /**
     * Get the cities associated with the province.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cities()
    {
        return $this->hasMany(City::class);
    }

    /**
     * Get the districts associated with the province.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function districts()
    {
        return $this->hasManyThrough(District::class, City::class);
    }

    /**
     * Get the villages associated with the province.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function villages()
    {
        return $this->hasManyThrough(Village::class, District::class);
    }

    /**
     * Get the userDetails associated with the province.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userDetails()
    {
        return $this->hasMany(UserDetail::class);
    }
}
