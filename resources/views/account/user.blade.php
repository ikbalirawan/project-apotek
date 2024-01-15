@extends('layouts.template')

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
    <div class="d-flex justify-content-end py-2"><a href="{{ route('account.create') }}" class="btn btn-secondary p-2">Tambah Data</a></div>
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1 @endphp
            @foreach ($users as $item)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['email'] }}</td>
                    <td>{{ $item['role'] }}</td>
                    <td class="d-flex justify-content-center">
                        <a href="{{ route('account.edit', $item['id']) }}" class="btn btn-primary me-3">Edit</a>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">Hapus</button>
                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Konfirmasi Hapus</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                Yakin ingin menghapus data ini?
                                </div>
                                <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <form action="{{ route('account.delete', $item['id']) }}" method="post" class="ms-3">
                                    @csrf
                                    @method('DELETE')
                                <button type="submit" class="btn btn-primary">Hapus</button>
                                </form>
                                </div>
                            </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
    </table>

    {{-- pagination --}}
    <div class="d-flex justify-content-end">
        @if ($users->count())
            {{ $users->links() }}
        @endif
    </div>
    
@endsection