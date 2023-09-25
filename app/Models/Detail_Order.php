<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail_Order extends Model
{
    use HasFactory;
    
    protected $table = 'table_detail_order';
    protected $primaryKey = 'id';
    protected $fillable = ['order_id','product_id','quantity'];
    protected $hidden = ['product_id'];

    public function product() {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }
}
