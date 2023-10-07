<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'table_cart';
    Protected $primaryKey = 'id';
    protected $fillable = ['product_id','quantity','carted_by'];
    protected $hidden = ['product_id','carted_by','user'];

    public function product() {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function user() {
        return $this->belongsTo('App\Models\User', 'carted_by');
    }
}
