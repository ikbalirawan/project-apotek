@extends('layouts.template')

{{-- mengisi bagian dinamis (yield) --}}
@section('content')
    <div class="jumbotron p-4 bg-light mt-5">
        <div class="container">
            @if (Session::get('failed'))
                <div class="alert alert-danger">{{ Session::get('failed') }}</div>
            @endif
            <h1 class="display-4">Apotek App</h1>
            {{-- Mengambil data dari table users sesuai data login --}}
            <h5>Selamat Datang, {{ Auth::user()->name }}</h5>
            <hr class="my-4">
            <p class="lead">Aplikasi manajemen untuk pekerja administrator apotek. Digunakan untuk admin logistik dan kasir.</p>
        </div>
    </div>
@endsection