<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MovieExport;
use Yajra\DataTables\Facades\DataTables;

class MovieController extends Controller
{
    /**
     * Tampilkan daftar semua film di halaman admin.
     */
    public function index()
    {
        $movies = Movie::all();
        return view('admin.movie.index', compact('movies'));
    }

   public function chart() {
        $filmActive = Movie::where('actived', 1)->count(); // yang diperlukan hanya jumlah, count()
        $filmNonActive = Movie::where('actived', 0)->count();
        $data = [$filmActive, $filmNonActive];
        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * DataTables untuk data film.
     */
    public function datatables()
    {
        $movie = Movie::query();

        return DataTables::of($movie)
            ->addIndexColumn()
            ->addColumn('poster_img', function ($movie) {
                $url = asset('storage/' . $movie->poster);
                return '<img src="' . $url . '" width="70">';
            })
            ->addColumn('actived_badge', function ($movie) {
                return $movie->actived
                    ? '<span class="badge bg-success">Aktif</span>'
                    : '<span class="badge bg-danger">Non-Aktif</span>';
            })
            ->addColumn('action', function ($movie) {
                $btnDetail = '<button type="button" class="btn btn-secondary me-2" onclick=\'showModal(' . json_encode($movie) . ')\'>Detail</button>';
                $btnEdit = '<a href="' . route('admin.movies.edit', $movie->id) . '" class="btn btn-primary me-2">Edit</a>';

                $btnDelete = '
                    <form action="' . route('admin.movies.destroy', $movie->id) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger me-2">Hapus</button>
                    </form>';

                $btnNonAktif = '';
                if ($movie->actived) {
                    $btnNonAktif = '
                        <form action="' . route('admin.movies.nonaktif', $movie->id) . '" method="POST" style="display:inline;">
                            ' . csrf_field() . method_field('PUT') . '
                            <button type="submit" class="btn btn-warning">Non-Aktifkan Film</button>
                        </form>';
                }

                return '<div class="d-flex justify-content-center align-items-center gap-2">'
                    . $btnDetail . $btnEdit . $btnDelete . $btnNonAktif . '</div>';
            })
            ->rawColumns(['poster_img', 'actived_badge', 'action'])
            ->make(true);
    }

    /**
     * Halaman Home menampilkan 3 film terbaru yang aktif.
     */
    public function home()
    {
        $movies = Movie::where('actived', 1)
            ->orderBy('created_at', 'DESC')
            ->limit(3)
            ->get();

        return view('home', compact('movies'));
    }

    /**
     * Halaman semua film aktif (dengan pencarian).
     */
    public function homeMovies(Request $request)
    {
        $nameMovie = $request->search_movie;

        $movies = Movie::where('actived', 1)
            ->when($nameMovie, function ($query, $nameMovie) {
                return $query->where('title', 'LIKE', '%' . $nameMovie . '%');
            })
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('movies', compact('movies'));
    }

    /**
     * Detail jadwal dari film tertentu.
     */
    public function moviesSchedule($movie_id)
    {
        $movie = Movie::where('id', $movie_id)
            ->with(['schedules', 'schedules.cinema'])
            ->firstOrFail();

        return view('schedule.detail', compact('movie'));
    }

    /**
     * Aktifkan film.
     */
    public function active($id)
    {
        Movie::where('id', $id)->update(['actived' => 1]);
        return redirect()->route('admin.movies.index')->with('success', 'Film berhasil diaktifkan.');
    }

    /**
     * Nonaktifkan film.
     */
    public function nonaktif($id)
    {
        Movie::where('id', $id)->update(['actived' => 0]);
        return redirect()->route('admin.movies.index')->with('success', 'Film berhasil dinonaktifkan.');
    }

    /**
     * Tampilkan form tambah film.
     */
    public function create()
    {
        return view('admin.movie.create');
    }

    /**
     * Simpan film baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required',
            'genre'       => 'required',
            'duration'    => 'required',
            'age_rating'  => 'required',
            'director'    => 'required',
            'poster'      => 'required|image|mimes:jpeg,png,jpg,svg,webp',
            'description' => 'required|min:10',
        ]);

        $poster = $request->file('poster');
        $fileName = time() . '-' . uniqid() . '.' . $poster->getClientOriginalExtension();
        $filePath = $poster->storeAs('poster', $fileName, 'public');

        Movie::create([
            'title'       => $request->title,
            'genre'       => $request->genre,
            'duration'    => $request->duration,
            'age_rating'  => $request->age_rating,
            'director'    => $request->director,
            'description' => $request->description,
            'poster'      => $filePath,
            'actived'     => 1,
        ]);

        return redirect()->route('admin.movies.index')->with('success', 'Film berhasil ditambahkan.');
    }

    /**
     * Form edit film.
     */
    public function edit($id)
    {
        $movie = Movie::findOrFail($id);
        return view('admin.movie.edit', compact('movie'));
    }

    /**
     * Update film.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'       => 'required',
            'genre'       => 'required',
            'duration'    => 'required',
            'age_rating'  => 'required',
            'director'    => 'required',
            'poster'      => 'mimes:jpeg,png,jpg,svg,webp',
            'description' => 'required|min:10',
        ]);

        $movie = Movie::findOrFail($id);
        $path = $movie->poster;

        if ($request->hasFile('poster')) {
            if (Storage::disk('public')->exists($movie->poster)) {
                Storage::disk('public')->delete($movie->poster);
            }

            $file = $request->file('poster');
            $fileName = 'poster-' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('poster', $fileName, 'public');
        }

        $movie->update([
            'title'       => $request->title,
            'genre'       => $request->genre,
            'duration'    => $request->duration,
            'age_rating'  => $request->age_rating,
            'director'    => $request->director,
            'poster'      => $path,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.movies.index')->with('success', 'Data film berhasil diperbarui.');
    }

    /**
     * Hapus film (soft delete).
     */
    public function destroy($id)
    {
        $movie = Movie::findOrFail($id);

        if ($movie->poster && Storage::disk('public')->exists($movie->poster)) {
            Storage::disk('public')->delete($movie->poster);
        }

        $movie->delete();

        return redirect()->route('admin.movies.index')->with('success', 'Film berhasil dihapus (masuk Recycle Bin).');
    }

    /**
     * Export data film ke Excel.
     */
    public function export()
    {
        return Excel::download(new MovieExport, 'data-film.xlsx');
    }

    /**
     * Tampilkan daftar film di Recycle Bin.
     */
    public function trash()
    {
        $moviesTrash = Movie::onlyTrashed()->get();
        return view('admin.movie.trash', compact('moviesTrash'));
    }

    /**
     * Restore film dari Recycle Bin.
     */
    public function restore($id)
    {
        $movie = Movie::onlyTrashed()->findOrFail($id);
        $movie->restore();

        return redirect()->route('admin.movies.index')->with('success', 'Film berhasil dikembalikan.');
    }

    /**
     * Hapus permanen film.
     */
    public function deletePermanent($id)
    {
        $movie = Movie::onlyTrashed()->findOrFail($id);

        if ($movie->poster && Storage::disk('public')->exists($movie->poster)) {
            Storage::disk('public')->delete($movie->poster);
        }

        $movie->forceDelete();

        return redirect()->route('admin.movies.index')->with('success', 'Film berhasil dihapus permanen.');
    }
}
