@extends ('layouts.template')

@section('content')

    <div class="container mt-3">
        <form action="{{ route('order.store') }}" class="card m-auto p-5" method="POST">
            @csrf
            @if ($errors->any())
                <ul class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
                <p>Penanggung Jawab : <b>{{ Auth::user()->name }}</b></p>
                <div class="mb-3 row">
                    <label for="name_customer" class="col-sm-2 col-form-label">Nama Pembeli</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="name_customer" id="name_customer">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="medicines" class="col-sm-2 col-form-label">Obat</label>
                    <div class="col-sm-10">
                        {{-- name dengan [] biasanya dipake buat column yang tipe datanya json/array, dan biasanya digunakan apabila input dengan tujuan data yang sama ada banyak  (dan dari banyak input yang datanya sama tersebut, datanya akan diambil seluruhnya dalam bentuk array ) --}}
                        <select name="medicines[]" id="medicines" class="form-select">
                            <option selected hidden disabled>Pesanan 1</option>
                            @foreach($medicines as $item)
                                <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                            @endforeach
                        </select>
                        {{-- karena akan ada JS yang menampilkan select ketika di klik, maka sediakan tempat penyimpanan element yang akan dihasilkan dari JS tersebut --}}
                    <div id="wrap-medicines"></div>
                    <br>
                    <p style="cursor: pointer" class="text-primary" id="add-select">+ Tambah Obat</p>
                    </div>
                </div>
                <button style="submit" class="btn btn-block btn-lg btn-primary">Konfirmasi Pembelian</button>
        </form>
    </div>

@endsection

@push('script')
    <script type="text/javascript">
        let no = 2;

        $("#add-select").on("click", function(){
            let el = `<br><select name="medicines[]" id="medicines" class="form-select">
                <option selected hidden disabled>Pesanan ${no}</option>
                @foreach($medicines as $item)
                    <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                @endforeach
                </select>`;

                $("#wrap-medicines").append(el);

                no++;
        });
    </script>
@endpush