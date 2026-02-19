<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relay extends Model
{
    protected $table = 'relay';   // 🔥 WAJIB (karena tabel kamu relay)
    protected $fillable = ['status'];
    public $timestamps = true;
}
