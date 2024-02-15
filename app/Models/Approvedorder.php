<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approvedorder extends Model
{
    use HasFactory;

    public function UserData() {

        return $this->hasOne(User::class , 'id','user_id');
    }
}
