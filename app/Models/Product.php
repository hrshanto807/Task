<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'description', 'price'];

    // app/Models/Product.php

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product');
        // Adjust table name if your pivot table is different
    }

}

