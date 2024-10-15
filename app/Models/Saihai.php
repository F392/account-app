<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\SerializeDate;

class Saihai extends Model
{
    use SerializeDate;

    protected $table = 'saihai';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id','store_id','crew_id','customer_bill_id','end_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
        'end_at'    => 'datetime',
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

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }


}
