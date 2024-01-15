{{-- memanggil file template --}}
@extends('layouts.template')

{{-- isi bagian yield --}}
@section('content')
@if (Session::get('deleted'))
    <div class="alert alert-warning">{{ Session::get('deleted') }}</div>
@endif
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
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Tipe</th>
                <th>Harga</th>
                <th>Stok</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1 @endphp
            @foreach ($medicines as $item)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['type'] }}</td>
                    <td>{{ $item['price'] }}</td>
                    <td>{{ $item['stock'] }}</td>
                    <td class="d-flex justify-content-center">
                        <a href="{{ route('medicine.edit', $item['id']) }}" class="btn btn-primary me-3">Edit</a>
                        {{-- method ::delete tidak bisa digunakan pada a href, harus melalui form action  --}}
                        <form action="{{ route('medicine.delete', $item['id']) }}" method="post" class="ms-3">
                        @csrf
                        {{-- menimpa/mengubah method="post" agar menjadi method="delete" sesuai dengan method route (::delete) --}}
                        @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
        </tbody>
    </table>

    {{-- pagination --}}
    <div class="d-flex justify-content-end pangita">
        @if ($medicines->count())
            {{ $medicines->links() }}
        @endif
    </div>
    
@endsection