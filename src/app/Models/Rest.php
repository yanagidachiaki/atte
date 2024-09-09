<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rest extends Model
{
    use HasFactory;

     public $timestamps = false;

    protected $fillable = ['start', 'stop', 'total', 'user_id'];
    public function rests()
    {
        return $this->belongsTo(Work::class);
    }
}
