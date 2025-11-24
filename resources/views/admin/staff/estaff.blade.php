@extends('templates.app')

@section('content')
<div class="container">
    <h3>Edit Data Pengguna</h3>

    <form action="{{ route('admin.users.ustaff', $users->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Nama Lengkap</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $users->name) }}" required>
            @error('name')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $users->email) }}" required>
            @error('email')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password <small>(Kosongkan jika tidak diubah)</small></label>
            <input type="password" name="password" id="password" class="form-control">
            @error('password')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select name="role" id="role" class="form-select" required>
                <option value="staff" {{ old('role', $users->role) == 'staff' ? 'selected' : '' }}>Staff</option>
                <option value="admin" {{ old('role', $users->role) == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            @error('role')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        <button type="submit" class="btn btn-primary">UPDATE</button>
        <a href="#" class="btn btn-secondary">BATAL</a>
    </form>
</div>
@endsection
