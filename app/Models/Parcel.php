<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parcel extends Model
{
    protected $fillable = ['sender_email', 'receiver_email', 'tracking_number'];
}
