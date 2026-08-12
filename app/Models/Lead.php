<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'lead_id',
        'full_name',
        'phone_number',
        'email',
        'city',
        'interest',
        'inbox_url',
        'created_time'
    ];
}
