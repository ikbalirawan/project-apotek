@extends('layouts.template')

@section('content')
<form action="{{ route('account.store') }}" method="post" class="card bg-light mt-5 p-5">
    @csrf
    @if (Session::get('success'))
    <div class="alert alert-success"> {{ Session::get('success') }} </div>
    @endif
    @if ($errors->any())
    <ul class='alert alert-danger p-5'>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    @endif
    <div class="mb-3 row">
      <label for="name" class="col-sm-2 col-form-label">Nama : </label>
            <input type="text" class="form-control" id="name" name="name">
        </div>

        <div class="mb-3 row">
            <label for="email" class="col-sm-2 col-form-label">email : </label>
                  <input type="text" class="form-control" id="email" name="email">
              </div>

    <div class="mb-3 row">
      <label for="role" class="col-sm-2 col-form-label">Tipe Pengguna : </label>
            <select id="role" class="form-control" name="role">
                <option disabled hidden selected >Pilih</option>
                <option value="admin">Admin</option>
                <option value="cashier">Kasir</option>
            </select>
        </div>    

    <button type="submit" class="btn btn-primary mt-3">Simpan</button>
  </form>
@endsection