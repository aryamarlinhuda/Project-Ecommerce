<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $table = 'table_favorite';
    Protected $primaryKey = 'id';
    protected $fillable = ['product_id','favorite_by'];
    protected $hidden = ['product_id','favorite_by','product','user'];

    public function product() {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function user() {
        return $this->belongsTo('App\Models\User', 'favorite_by');
    }
}
