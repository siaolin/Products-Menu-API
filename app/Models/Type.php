<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $fillable =[
        'name',
        'sort',
    ];
    /**
     * 取得類別的商品
     */
    public function product() {
        return $this->hasMany('App\Product', 'type_id', 'id');
    }
}
