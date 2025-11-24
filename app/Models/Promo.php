<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promo extends Model
{
    use SoftDeletes; // <- ini pakai "SoftDeletes" bukan "SoftDelete"

    protected $fillable = ['promo_code', 'discount', 'type', 'actived'];
}
