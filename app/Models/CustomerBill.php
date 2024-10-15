<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\SerializeDate;

class CustomerBill extends Model
{
    use SerializeDate;

    protected $table = 'customer_bills';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id','crew_id','date','people_number','cash_bill','credit_bill','kake_bill','intax_bill','notax_bill','flag'
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

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }
}
