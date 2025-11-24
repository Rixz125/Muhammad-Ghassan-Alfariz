@extends('templates.app')

@section('content')

<div class="w-75 d-block mx-auto my-5 p-4">
    <h5 class="text-center mb-3">Edit data Bioskop</h5>
    <form method="POST" action="{{ route('admin.cinemas.update', ['id' => $cinema->id]) }}">
        @csrf
        {{-- menimpa method post ke html put --}}
        @method('PUT')
        @error('name')
            <small class="alert alert-danger">{{ $message }}</small>
        @enderror
        <div class="mb-3">
            <label for="name" class="form-label">Nama Bioskop</label>
            <input type="text" name="name" id="name" class="form-control @error ('name') is-inavalid @enderror" value="{{ $cinema->name }}">
</div>
        @error('location')
            <small class="alert alert-danger">{{ $message }}</small>
        @enderror
        <div class="mb-3">
            <label for="location" class="form-label">Lokasi</label>
            <input type="text" name="location" id="location" class="form-control @error ('location') is-inavalid @enderror" value="{{ $cinema->location }}">
        </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection