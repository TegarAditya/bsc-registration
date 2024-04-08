<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    use HasFactory;

    protected $fillable = ['district_id', 'code', 'name'];

    /**
     * Get the province that the village belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToThrough
     */
    public function province()
    {
        return $this->belongsToThrough(Province::class, City::class, District::class);
    }

    /**
     * Get the city that the village belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToThrough
     */
    public function city()
    {
        return $this->belongsToThrough(City::class, District::class);
    }

    /**
     * Get the district that the village belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Apply filters to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  array  $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('code', 'like', '%'.$search.'%')
                ->orWhere('name', 'like', '%'.$search.'%');
        });
    }
}
