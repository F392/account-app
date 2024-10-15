<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\SerializeDate;

class Cashier extends Model
{
    use SerializeDate;
    
    protected $table = 'cashiers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id','store_id','date','ex_cashier_bill'
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
}
