<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\SerializeDate;

class MukeiBill extends Model
{
    use SerializeDate;

    protected $table = 'mukei_bills';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_bill_id','mukei_crew_id','mukei_bill'
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

    public function customer_bill()
    {
        return $this->belongsTo('App\Models\CustomerBill');
    }

}
