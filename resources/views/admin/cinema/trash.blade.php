@extends('templates.app')

@section('content')
    <div class="container my-5">
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.cinemas.index') }}" class="btn btn-secondary">Kembali</a>
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
            @foreach ( $cinemasTrash as $key =>$cinema)
               <tr>
    <td>{{ $key + 1 }}</td>
    <td>{{ $cinema->name }}</td>
    <td>{{ $cinema->address }}</td>
    <td>{{ $cinema->city }}</td>
    <td class="d-flex">
        <form action="{{ route('admin.cinemas.restore', $cinema->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <button class="btn btn-success btn-sm ms-2">Kembalikan</button>
        </form>
        <form action="{{ route('admin.cinemas.delete_permanent', $cinema->id) }}" method="POST" class="ms-2">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger btn-sm ms-2">Hapus</button>
        </form>
    </td>
</tr>

            @endforeach
        </table>
    </div>
@endsection
