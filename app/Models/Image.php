<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $table = 'product_image';
    protected $primaryKey = 'id';
    protected $fillable = ['image','product_id'];
    protected $hidden = ['image','product_id','product'];

    public function product() {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }
}
