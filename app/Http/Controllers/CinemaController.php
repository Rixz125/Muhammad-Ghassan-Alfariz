<?php

namespace App\Http\Controllers;

use App\Models\Cinema;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CinemaExport;

class CinemaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cinemas = Cinema::all();
        return view('admin.cinema.index', compact('cinemas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.cinema.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:500',
        ],[
            'name.required' => 'Nama bioskop wajib diisi.',
            'location.required' => 'Lokasi bioskop wajib diisi.',
            'location.min' => 'Lokasi bioskop minimal 10 karakter.',
        ]);
        $createData = Cinema::create([
            'name' => $request->name,
            'location' => $request->location,
        ]);
        if ($createData){
            return redirect()->route('admin.cinemas.index')->with('success', 'Data bioskop berhasil ditambahkan.');
        } else {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan data bioskop. Silakan coba lagi.');
        }
 }

    /**
     * Display the specified resource.
     */
    public function show(Cinema $cinema)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cinema = Cinema::find($id);
        // dd($cinema ->toArray());
        return view('admin.cinema.edit', compact('cinema'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //reuqest diambil data form , $id ambil parameter placeholder (id) dari route
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|min:10',
        ],[
            'name.required' => 'Nama bioskop wajib diisi.',
            'location.required' => 'Lokasi bioskop wajib diisi.',
            'location.min' => 'Lokasi bioskop minimal 10 karakter.',
        ]);
        $updateData = Cinema::where('id', $id)->update([
            'name' => $request->name,
            'location' => $request->location,
        ]);
        if ($updateData){
            return redirect()->route('admin.cinemas.index')->with('success', 'Data bioskop berhasil diupdate.');
        } else {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengupdate data bioskop. Silakan coba lagi.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
            $Schedule= Schedule::where('cinema_id', $id)->count();
            if($Schedule){
             return redirect()->route('admin.cinemas.index')->with('error', 'tidak dapat di hapus data bertaut dengan jadwal tayang');
             } 

        Cinema::where('id', $id)->delete();
        return redirect()->route('admin.cinemas.index')->with
        ('success', 'Berhasil menghapus data');
    }
    // method export
  public function export()
  {
    // nama file yang akan di download
    // ekstensi 
    $fileName= "data-film.xlsx";
    return Excel::download(new CinemaExport, $fileName);
  }

  
    public function trash()
    {
        $cinemasTrash = Cinema::onlyTrashed()->get();
        return view('admin.cinema.trash', compact('cinemasTrash'));
    }

    public function restore($id)
    {
        $cinema = Cinema::onlyTrashed()->where('id', $id)->first();
        $cinema->restore();
        return redirect()->route('admin.cinemas.trash')->with('success', 'Berhasil mengembalikan data');
    }

    public function deletePermanent($id)
    {
        $cinema = Cinema::onlyTrashed()->find($id);
        $cinema->forceDelete();
        return redirect()->back()->with('success', 'Berhasil menghapus data secara permanen');
    }

    public function cinemaList() {
        $cinemas = Cinema::all();
        return view('schedule.cinemas', compact('cinemas'));
    }

        public function cinemaSchedules($cinema_id)
        // wherehas nama relasi function  $q  {---} argumen 1 nama relasi wajib,
        // argumen 2 Func untuk filter pda relasi optional
        // wherehas nama relasi uuuu
        {
            $schedules = Schedule::where('cinema_id', $cinema_id)->with('movie')->whereHas
            ('movie', function($q) {
                $q->where('actived', 1);
            })->get();
            return view('schedule.cinema-schedules', compact('schedules'));
        }
}
