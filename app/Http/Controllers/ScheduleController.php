<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Cinema;
use App\Models\Movie;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Exports\ScheduleExport;
use Maatwebsite\Excel\Facades\Excel;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cinemas = Cinema::all();
        $movies = Movie::all();
        // with() memanggil relasi detail, bukan hanya id-nya
        $schedules = Schedule::with(['cinema', 'movie'])->get();

        return view('staff.schedule.index', compact('cinemas', 'movies', 'schedules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cinema_id' => 'required',
            'movie_id'  => 'required',
            'price'     => 'required|numeric',
            'hours.*'   => 'required|date_format:H:i',
        ], [
            'cinema_id.required' => 'Bioskop harus dipilih.',
            'movie_id.required'  => 'Film harus dipilih.',
            'price.required'     => 'Harga harus diisi.',
            'price.numeric'      => 'Harga harus berupa angka.',
            'hours.*.required'   => 'Jam wajib diisi.',
            'hours.*.date_format'=> 'Format jam harus HH:MM.',
        ]);

        // Cek apakah data dengan cinema_id & movie_id sudah ada
        $existingSchedule = Schedule::where('cinema_id', $request->cinema_id)
            ->where('movie_id', $request->movie_id)
            ->first();

        $hoursBefore = $existingSchedule ? $existingSchedule->hours : [];
        $mergeHours = array_merge($hoursBefore, $request->hours);
        $newHours = array_unique($mergeHours);

        // updateOrCreate: jika ada data lama, update; jika belum, buat baru
        $createData = Schedule::updateOrCreate(
            [
                'cinema_id' => $request->cinema_id,
                'movie_id'  => $request->movie_id,
            ],
            [
                'price' => $request->price,
                'hours' => $newHours,
            ]
        );

        if ($createData) {
            return redirect()->route('staff.schedules.index')->with('success', 'Data berhasil ditambahkan.');
        } else {
            return redirect()->back()->with('error', 'Gagal menambahkan data.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule, $id)
    {
        $schedule = Schedule::where('id', $id)->with(['cinema', 'movie'])->first();
        return view('staff.schedule.edit', compact('schedule'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule, $id)
    {
        $request->validate([
            'price' => 'required|numeric',
            'hours.*' => 'required|date_format:H:i',
        ],[
            'price.required' => 'Harga harus ini di isi',
            'price.numeric' => 'Harga harus di isi dengan angka',
            'hours.*.required' => 'Jam tayang harus di isi',
            'hours.*.date_format' => 'Jam tayang harus di isi dengan format Jam:menit',
        ]);

        $updateData = Schedule::where('id', $id)->update([
            'price' => $request->price,
            'hours' => $request->hours,
        ]);

        if($updateData){
            return redirect()->route('staff.schedules.index')->with('success', 'Berhasil mengubah data');
        } else {
            return redirect()->back()->with('error', 'Gagal Coba lagi');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule, $id)
    {
        $Schedule = Schedule::where('id', $id)->delete();
            return redirect()->route('staff.schedules.index')->with('success', 'Berhasil Menghapus data');
    }

    public function trash()
    {
        $schedulesTrash = Schedule::onlyTrashed()->with(['cinema', 'movie'])->get();
        return view('staff.schedule.trash', compact('schedulesTrash'));
    }

    public function restore($id)
    {
        $schedule = Schedule::onlyTrashed()->where('id', $id)->first();
        $schedule->restore();
        return redirect()->route('staff.schedules.trash')->with('success', 'Berhasil mengembalikan data');
    }

    public function deletePermanent($id)
    {
        $schedule = Schedule::onlyTrashed()->find($id);
        $schedule->forceDelete();
        return redirect()->back()->with('success', 'Berhasil menghapus data secara permanen');
    }

        public function export()
    {
        return Excel::download(new ScheduleExport, 'data_jadwal.xlsx');
    }

    public function showSeats($scheduleId, $hourId)
    {
        $schedule = Schedule::where('id', $scheduleId)->with('cinema')->first();
        $hour = $schedule['hours'][$hourId];
        // ambil data kursi denga kriteria
        $seats = Ticket::where('schedule_id', $scheduleId)->whereHas('TicketPayment',
        function($q) {
            $date = now()->format('Y-m-d');
            $q->whereDate('paid_date', $date);
        })->whereTime('hour', $hour)->pluck('rows_of_seats');
        // pluck : mengambil data hanya satu colum
        // mengganti aray dua di mensi emnjadi satu dimensi
        $seatsFormat = array_merge(...$seats);
        // .... : speread operato
        // dd($seats);
        return view('schedule.show-seats', compact('schedule', 'hour', 'seatsFormat'));
    }
}
