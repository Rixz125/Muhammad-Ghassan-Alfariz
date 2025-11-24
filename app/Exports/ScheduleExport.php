<?php

namespace App\Exports;

use App\Models\Schedule;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ScheduleExport implements FromCollection, WithHeadings, WithMapping
{
    private $key = 0;

    /**
     * Ambil data jadwal beserta relasi movie dan cinema.
     */
    public function collection()
    {
        return Schedule::with(['movie', 'cinema'])
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    /**
     * Tentukan header kolom di Excel.
     */
    public function headings(): array
    {
        return [
            'No',
            'Nama Bioskop',
            'Judul Film',
            'Jadwal Tayang',
            'Harga Tiket',
        ];
    }

    /**
     * Tentukan isi setiap baris data.
     */
    public function map($schedule): array
    {
        return [
            ++$this->key,
            $schedule->cinema->name ?? '-',
            $schedule->movie->title ?? '-',
            $schedule->hours ?? '-',
            $schedule->price
                ? 'Rp ' . number_format($schedule->price, 0, ',', '.')
                : '-',
        ];
    }
}
