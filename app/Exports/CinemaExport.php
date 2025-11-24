<?php

namespace App\Exports;

use App\Models\Cinema;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class CinemaExport implements FromCollection, WithHeadings, WithMapping
{
    private $key = 0;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Ambil data cinema yang akan diexport
        return Cinema::orderBy('created_at', 'DESC')->get();
    }

    // Judul kolom di Excel
    public function headings(): array
    {
        return [
            "No",
            "Nama Bioskop",
            "Lokasi",
        ];
    }

    // Data setiap baris (td)
    public function map($cinema): array
    {
        return [
            ++$this->key,
            $cinema->name,
            $cinema->location,

        ];
    }
}
