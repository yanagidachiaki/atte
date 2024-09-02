<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rest extends Model
{
    use HasFactory;

    protected $fillable = [
        'start',
        'stop'
    ];

    public function rests()
    {
        return $this->belongsTo(Work::class);
    }
}
