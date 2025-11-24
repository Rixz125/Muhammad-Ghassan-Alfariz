<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use SoftDeletes;

  protected $fillable = ['cinema_id', 'movie_id', 'hours', 'price'];

//   casts : memastikan forma data 

protected function casts() :array
{
    return [
        // mengubah format json migratio hours jd arry 
        'hours' => 'array'
    ];
}
  // pegang posisi kedua  panggil rlasi dengan belongsTo
  // Cinema pegnang posisi pertama dan jenis (one) jd gunakan tunggal

  public function cinema() {
    return $this->belongsTo(Cinema::class);

   }

   public function movie() {
    return $this->belongsTo(Movie::class);

   }

}
