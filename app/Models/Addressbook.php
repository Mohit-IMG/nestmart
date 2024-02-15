<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addressbook extends Model
{
    use HasFactory;

    protected $fillable = [
        'address_line1',
        'address_line2',
        'pincode',
        'state_id',
        'city_id',
    ];
}
