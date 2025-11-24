@extends('templates.app')

@section('content')
    <div class="container my-5">
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.movies.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
        <h3 class="my-3">Data Sampah Jadwal Tayang</h3>
         @if (Session::get('success'))
            <div class="alert alert-success">
                {{ Session::get('success') }}</div>
        @endif
        <table class="table table-bordered">
            <tr>
                <th>#</th>
                <th>Nama Bioskop</th>
                <th>Judul Film</th>
                <th>Aksi</th>
            </tr>
            @foreach ( $moviesTrash as $key =>$movie)
  <tr>
    <td>{{ $key + 1 }}</td>
    <td>{{ $movie->title }}</td>
    <td>{{ $movie->genre }}</td>
    <td>{{ $movie->duration }} menit</td>
    <td class="d-flex">
        <form action="{{ route('admin.movies.restore', $movie->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-success btn-sm">Kembalikan</button>
        </form>

        <form action="{{ route('admin.movies.delete_permanent', $movie->id) }}" method="POST" class="ms-2">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">Hapus Permanen</button>
        </form>
    </td>
</tr>


            @endforeach
        </table>
    </div>
@endsection
