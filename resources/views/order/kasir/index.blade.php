@extends('layouts.template')

@section('content')
    <div class="container mt-3">
        <div class="row">
            <div class="col-6">
                <form action="{{ route('order.search') }}" method="GET">
                    @csrf
                    <div class="input-group">
                        <input type="date" class="form-control" placeholder="Recipient's username"
                            aria-label="Recipient's username with two button addons" name="search">
                        <button class="btn btn-info" type="submit">Cari Data</button>
                        <a class="btn btn-secondary" type="submit" href="{{ route('order.index') }}" >Reset</a>
                    </div>
                </form>
            </div>
            <div class="col-3"></div>
            <div class="col-3">
                <div class="d-flex justify-content-end">
                    <a href=" {{ route('order.create') }}" class="btn btn-primary">🛒</a>
                </div>
            </div>
        </div>
    </div>
    <table class="table table-striped table-bordered table-hover w-100 mt-3">
        <thead>
            <tr>
                <th>No</th>
                <th>Pembeli</th>
                <th>Obat</th>
                <th>Total Bayar</th>
                <th>Kasir</th>
                <th>Tanggal</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    {{-- current : ambil posisi ada di page ke berapa - 1 (misal udah klik next lg ada di pagw 2 berarti jadi 2 - 1 = 1), perpage : mengambil jumlah data yang ditampilkan perpage nya berapa (ada di controller bagian paginate / simplePaginate, misal 5), loop->index : mengambil index dari array (mulai dari 0)+1 --}}
                    <td>{{ ($orders->currentpage()-1) * $orders->perpage() + $loop->index + 1 }}</td>
                    <td>{{ $order['name_customer'] }}</td>
                    {{-- nested loop : looping didalam loping --}}
                    {{-- karna column medicines pada table orders tipe datanya json, jadi untuk akses nya perlu looping --}}
                    <td>
                        <ol>
                            @foreach ($order['medicines'] as $medicine)
                                <li>{{ $medicine['name_medicine'] }} <small>Rp.
                                        {{ number_format($medicine['price'], 0, '.', ',') }} <b>(qty :
                                            {{ $medicine['qty'] }})</b></small> = Rp.
                                    {{ number_format($medicine['sub_price'], 0, '.', ',') }} </li>
                            @endforeach
                        </ol>
                    </td>
                    @php
                        $ppn = $order['total_price'] * 0.01;
                    @endphp
                    <td>Rp. {{ number_format($order['total_price'] + $ppn, 0, '.', ',') }}</td>
                    {{-- mengambil column dari relasi, $variable['namaFunctionDiModel']['namaColumnDiDBRelasi'] --}}
                    <td>{{ $order['user']['name'] }} <a href="mailto:{{ $order['user']['email'] }}">({{ $order['user']['email'] }})</a></td>
                    @php
                        // set lokasi waktu berdasarkan penamaan dan jam WIB Indonesia
                        setLocale(LC_ALL, 'IND');
                    @endphp
                    {{-- carbon : package bawaan laravel untuk memanipulasi format tanggal/waktu --}}
                    <td>{{ Carbon\Carbon::parse($order['created_at'])->formatLocalized('%d %B %Y') }}</td>
                    <td><a href="{{ route('order.download', $order['id']) }}" class="btn btn-primary">Cetak</a></td>
                </tr>
                @endforeach
            </tbody>
    </table>

            <div class="d-flex justify-content-end">
                @if ($orders->count())
                    {{ $orders->links() }}
                @endif
            </div>

        @endsection

