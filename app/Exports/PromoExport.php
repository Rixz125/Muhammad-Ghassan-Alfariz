<?php

namespace App\Exports;

use App\Models\Promo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class PromoExport implements FromCollection, WithHeadings, WithMapping
{
    private $key = 0;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Ambil semua data promo terbaru
        return Promo::orderBy('created_at', 'DESC')->get();
    }

    // Judul kolom di Excel
    public function headings(): array
    {
        return [
            "No",
            "Kode Promo",
            "Total Potongan",
        ];
    }

    // Data setiap baris (td)
   public function map($promo): array{
        return [
            ++$this->key,
            $promo->promo_code,
            //jika type percent maka menampilkan discount + %, jika rupiah maka menampilkan Rp + discount
            // ? : ini adalah if else dalam bentuk singkat, namanya adalah ternary operator
            $promo->type == 'percent' ? $promo->discount . '%' : 'Rp ' . number_format($promo->discount, 0, ',', '.')
        ];
    }
}
