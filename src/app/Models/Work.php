<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
   use HasFactory;

    public $timestamps = false; 

    protected $fillable = ['start', 'stop', 'user_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function rests()
    {
        return $this->hasMany(Rest::class, 'work_id');
    }
}
