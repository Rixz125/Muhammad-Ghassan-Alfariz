<?php

namespace App\Exports;

use App\Models\Movie;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
// use 
use Carbon\Carbon;


class MovieExport implements FromCollection, WithHeadings, WithMapping
{
    private $key = 0;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // menentukan data yg akan ditmbaahkan diexcel
        return Movie::orderBy('created_at', 'DESC')->get();
    }

    // menentukan th
    public function headings():array
{
    return ["No", 'judul', 'Durasi', 'Genre', 'Sutradara', 'Usia Minimal', 'Poster', 'Sinopsis', 'Status'];
}

// menentukan td
public function map($movie):array
{
    return [
        // menambahakan key diatas dr 1DST
        ++$this->key,
        $movie->title,
        // format h mengambil jam duration
        // menampilkan  menit duration
        Carbon::parse($movie->duration)->format("H") . "jam" . Carbon::parse($movie->duration)->format("I") . "menit",
        $movie->genre,
        $movie->director,
        $movie->age_rating,
        // 
        asset("storage") . "/" . $movie->poster,
        $movie->description,
        // 
        $movie->actived == 1 ? 'aktif' : 'non-aktif',

    ];
}
}
