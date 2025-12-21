@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <h1 class="h3 mb-4">Edit Admin</h1>

    <form action="{{ route('kel_role.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Role</label>
            <select name="role" class="form-control">
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="super_admin" {{ $user->role == 'super_admin' ? 'selected' : '' }}>
                    Super Admin
                </option>
            </select>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('kel_role.index') }}" class="btn btn-secondary">Kembali</a>
    </form>

</div>
@endsection
