@extends('templates.app')

@section('content')
    <div class="container">
        <h3>Data Pengguna (Admin & Staff)</h3>
         <a href="{{ route('admin.users.export') }}" class="btn btn-secondary mb-3 float-end">Export (.xlsx)</a>
         <a href="{{ route('admin.users.trash') }}" class="btn btn-warning mb-3"> Data Sampah</a>
        <a href="{{ route('admin.users.cstaff') }}" class="btn btn-success mb-3 float-end">TAMBAH DATA</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $index => $users)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $users->name }}</td>
                        <td>{{ $users->email }}</td>
                        <td>
                            @if ($users->role == 'admin')
                                <span class="badge badge-primary">Admin</span>
                            @else
                                <span class="badge badge-success">Staff</span>
                            @endif
                        </td>
                        <td class="d-flex gap-2">
                            <a href="{{ route('admin.users.estaff', $users->id) }}" class="btn btn-secondary">EDIT</a>

                            <form action="{{ route('admin.users.dstaff', $users->id) }}" method=POST onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">HAPUS</button>
                            </form>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
