@extends('layouts.template')

@section('content')
    <div id="msg-success"></div>

    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Stock</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($medicines as $item)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $item['name'] }}</td>
                    <td style="{{ $item['stock'] <= 3 ? 'background: red; color: white' : 'background: none; color: black' }}">{{ $item['stock'] }}</td>
                    <td class="d-flex justify-content-center">
                      <div onclick="edit({{$item['id']}})" class="btn btn-primary me-3" style="cursor: pointer">Tambah Stock</div>
                    </td>
                </tr>
                @endforeach
        </tbody>
    </table>

    {{-- Modal --}}
    <div class="modal" tabindex="-1" id="edit-stock">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-litle">Ubah Data Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
                </div>
                <form method="POST" id="form-stock">
                    <div class="modal-body">
                        <div id="msg"></div>
                        
                        {{-- input hidden tidak akan tertampil, biasanya digunakan untuk menyimpan data yang diperlukan di proses BE tapi tidak boleh diketahui/diubah user --}}
                        <input type="hidden" name="id" id="id">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Obat</label>
                            <input type="text" class="form-control" name="name" id="name" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="stock" class="form-label">Stock Obat :</label>
                            <input type="number" class="form-control" name="stock" id="stock">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endsection

    @push('script')
        <script type="text/javascript">
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
        });

        function edit(id) {
            // panggil route dari web.php yang akan mengangani proses ambil satu data
            var url = "{{ route('medicine.stock.edit', ":id") }}";
            // ganti bagian 'id' di url nya jadi data dari parameter id di function nya 
            url = url.replace(':id', id);

            // pengambilan data dari FE ke BE dijembati oleh jquery ajax
            $.ajax({
                // routenya pake method :: apa
                type:"GET",
                // link route nya dari link url
                url: url,
                // data yang dihasilin bentuknya json
                contentType: "json",
                // kalau proses ambil data berhasil, ambil data yang dikirim BE lewat parameter
                success: function (res) {
                    // munculkan modal yang id nya edit-stock
                    $('#edit-stock').modal('show');
                    // isi value input dari hasil response BE
                    $('#id').val(res.id);
                    $('#name').val(res.name);
                    $('#stock').val(res.stock);
                }
            });
        }
        
        //ketika form dengan id="form-stock" button submit nya di klik
        $('#form-stock').submit(function(e){
            //element form penanganan actionnya akan diambil alih (ditangani) oleh js
            e.preventDefault();
            //ambil value dari inputan id yang disembunyikan, untuk mengisi path {id} di routenya
            var id = $('#id').val();
            //route action penanganan update data
            var urlForm = "{{ route('medicine.stock.update', ":id") }}";
            //buat variable yang akan dikirim ke BE
            urlForm = urlForm.replace(':id', id);
            
            var data = {
                stock: $('#stock').val(),
            }

            $.ajax({
                type: "PATCH",
                url: urlForm,
                data: data,
                chace:false,
                success: (data) => {
                    //jika berhasil modal di hide
                    $("#edit-stock").modal('hide');
                    //buat session js bernama 'reloadAfterPageLoad'
                    sessionStorage.reloadAfterPageLoad = true;
                    window.location.reload();
                },
                error: function(data){
                    //kalau terjadi error, pada element id="msg" tambah class dengan value alert  alert-danger
                    $('#msg').attr("class", "alert alert-danger")
                    //isi text element id="msg" diambil dari responsejson bagian message
                    $('#msg').text(data.responseJSON.message);
                }
            });
        });

        //function tanpa nama akan dijalankan ketika web baru selaesai loading
        $(function() {
            if (sessionStorage.reloadAfterPageLoad){
                $('#msg-success').attr("class", "alert alert-success")
                $('#msg-success').text("Berhasil menambahkan data stock!");
                //hapus kembali data session setelah alert success dimunculkan
                sessionStorage.clear();
            }
        });
        
        </script>
        @endpush
