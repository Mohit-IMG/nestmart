<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales_detail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_status',
        'remark',
    ];
}
