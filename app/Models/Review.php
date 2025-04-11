<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Review extends Model
{
    protected $connection = 'mongodb';
    protected $table = 'reviews';

    protected $fillable = ['user_id', 'product_id', 'comment', 'rating', 'date'];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', '_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', '_id');
    }
}
