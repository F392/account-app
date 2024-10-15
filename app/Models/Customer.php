<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\SerializeDate;

class Customer extends Model
{
    use SerializeDate;

    protected $table = 'customers';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id','store_id','name','kana','company','crew_id','birthday','bottle','comment'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    public function store()
    {
        return $this->belongsTo('App\Models\Store');
    }

    public function crew()
    {
        return $this->belongsTo('App\Models\Crew');
    }
}
