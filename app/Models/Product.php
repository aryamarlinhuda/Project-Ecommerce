<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'table_product';
    protected $primaryKey = 'id';
    protected $fillable = ['name','description','category_id','stock','price'];
    protected $hidden = ['category_id','category'];

    public function category() {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }
}
