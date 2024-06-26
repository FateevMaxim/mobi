<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'whats_app'
    ];

    protected $hidden =
        [
            'created_at',
            'updated_at'
        ];
}
