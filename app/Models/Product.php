<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_id',
        'product_name',
        'product_description',
        'price',
    ]; 
    /**
     * 取得商品的類別
     */
    public function type() {
        return $this->belongsTo(Type::class);
    }
    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        $array = parent::toArray();
        
        // 格式化 created_at 和 updated_at
        $array['created_at'] = $this->created_at->format('Y-m-d H:i');
        $array['updated_at'] = $this->updated_at->format('Y-m-d H:i');
        
        return $array;
    }
}
