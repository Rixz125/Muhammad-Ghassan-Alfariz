@extends('templates.app')

@section('content')
    <div class="container mt-5">
        @if (Session::get('success'))
        <div class="alert alert-success">{{Session::get('success')}}</div>
        @endif
        @if (Session::get('error'))
        <div class="alert alert-danger">{{Session::get('error')}}</div>
        @endif
        <div class="d-flex justify-content-end">
            
            <a href="{{ route('admin.cinemas.export') }}" class="btn btn-secondary">Export (.xlsx)</a>
            <a href="{{ route('admin.cinemas.trash') }}" class="btn btn-warning"> Data Sampah</a>
            <a href="{{ route('admin.cinemas.create')}}" class="btn btn-success ">Tambah Data</a>
        </div>
        <h5 class="mt-3">Data Bioskop</h5>
        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>Nama Bioskop</th>
                <th>Alamat</th>
                <th>Aksi</th>
            </tr>
            {{-- $cinemas dari compact , karena pakai all jadi aryy di mensi --}}
            @foreach ($cinemas as $index => $item)
                <tr>
                    {{-- index dair 0, biar ,muncul dari 1 -> +1 --}}
                    <th>{{ $index + 1 }}</th>
                    {{-- name , location dari filable model cinema --}}
                    <th>{{ $item['name'] }}</th>
                    <th>{{ $item['location'] }}</th>
                    <th class="d-flex">
                        <a href ="{{route('admin.cinemas.edit', ['id' => $item['id']])}}" class="btn btn-secondary">edit</a>
                        <form action="{{route('admin.cinemas.delete', ['id' => $item ['id']])}}" method="post">
                            @csrf
                            @method('DELETE')
                        <button type="submit" class="btn btn-danger">hapus</button>
                        </form>
                        
                    </th>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
