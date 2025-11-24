<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cinema extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'location',
    ];

    // karena cinema di pegangn posisi pertama (one to many : cinema dan schedules)
    // mendaftarkan jenis relasinya
    // nama ralasi tunggal / jamak tergantung jeninya . schedules (many) jamak

    public function schedules() {
        return $this->hasMany(ralated: Schedule::class);
     
      }
}
