@extends('templates.app')

@section('content')
    <div class="container my-5">
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.users.istaf') }}" class="btn btn-secondary">Kembali</a>
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
     @foreach ($usersTrash as $key => $user)
<tr>
    <td>{{ $key + 1 }}</td>
    <td>{{ $user->name }}</td>
    <td>{{ $user->email }}</td>
    <td>{{ $user->role }}</td>
    <td class="d-flex">
        {{-- Tombol Kembalikan --}}
        <form action="{{ route('admin.users.restore', $user->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-success btn-sm">Kembalikan</button>
        </form>

        {{-- Tombol Hapus Permanen --}}
        <form action="{{ route('admin.users.delete_permanent', $user->id) }}" method="POST" class="ms-2">
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
