<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = 'table_review';
    protected $primaryKey = 'id';
    protected $fillable = ['rating','review','product_id','created_by'];
    protected $hidden = ['product_id','created_by','product','user'];

    public function product() {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function user() {
        return $this->belongsTo('App\Models\User', 'created_by');
    }
}
