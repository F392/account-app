<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\SerializeDate;

class Store extends Model
{
    use SerializeDate;

    protected $table = 'stores';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id','name','image','zipcode','address','phone_number','email','responder','tax'
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

    public function customers()
    {
        return $this->hasMany('App\Models\Customer');
    }

    public function crew()
    {
        return $this->hasMany('App\Models\Crew');
    }

    public function tables()
    {
        return $this->hasMany('App\Models\Table');
    }

    public function suppliers()
    {
        return $this->hasMany('App\Models\Supplier');
    }

    public function supplier_bills()
    {
        return $this->hasMany('App\Models\SupplierBill');
    }

    public function daily_payments()
    {
        return $this->hasMany('App\Models\DailyPayments');
    }

    public function cashiers()
    {
        return $this->hasMany('App\Models\Cashier');
    }

    public function saihai()
    {
        return $this->hasMany('App\Models\Saihai');
    }

}
