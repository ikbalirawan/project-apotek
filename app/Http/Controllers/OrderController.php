<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;
use App\Exports\OrderExport;
use Excel;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // with : mengambil function relasi PK ke FK atau FK ke PK dari model 
        // isi di petik disamakan dengan nama function di modelnya
        $orders = Order::with('user')->simplePaginate(5);
        // dd($orders)
        return view('order.kasir.index', compact('orders'));
    }
    
    public function admin()
    {
        // with : mengambil function relasi PK ke FK atau FK ke PK dari model 
        // isi di petik disamakan dengan nama function di modelnya
        $orders = Order::with('user')->simplePaginate(5);
        // dd($orders)
        return view('order.admin.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $medicines = Medicine::all();
        return view('order.kasir.create', compact('medicines'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_customer' => 'required',
            'medicines' => 'required',
        ]);

        // array_count_values : menghitung jumlah item sama didalam array
        // hasilnya berbentuk : "itemnya" => "jumlah yang sama"
        // menentukan qty
        $arrayDistinct = array_count_values($request->medicines);
        // penampung detail berbentuk array2 assoc dari obat2yang dipilih
        $arrayAssocMedicines = [];

        foreach ($arrayDistinct as $id => $count) {
            $medicine = Medicine::where('id', $id)->first();
            $subPrice = $medicine['price'] * $count;
            $arrayItem = [
                "id" => $id,
                "name_medicine" => $medicine['name'],
                "qty" => $count,
                // (int) => memastikan dan mengubah tipe data menjadi integer
                "price" => $medicine['price'],
                "sub_price" => $subPrice,
            ];

            // format assoc dimasukkan ke array penampung sebelumnya
            array_push($arrayAssocMedicines, $arrayItem);
        }

        //var total price awalnya 0
        $totalPrice = 0;
        // loop data dari array penampung yang ada di format
        foreach ($arrayAssocMedicines as $item) {
            // dia bakal menjalankan totalPrice sebelumnya ditambah data harga dari price_after_qty
            $totalPrice += (int)$item['sub_price'];
        }

        $priceWithPPN = $totalPrice + ($totalPrice * 0.01);

        $proses = Order::create([
            'user_id' => Auth::user()->id,
            'medicines' => $arrayAssocMedicines,
            'name_customer' => $request->name_customer,
            'total_price' => $priceWithPPN,
            // user_id menyimpan data id dari orang yang login (kasir penanggung jawab)
        ]);

        if ($proses) {
            $order = Order::where("user_id", Auth::user()->id)->orderBy('created_at', 'DESC')->first();
            // redirect ke halaman print
            return redirect()->route('order.print', $order['id']);
        }else{
            return redirect()->back()->with('failed', 'Gagal membuat data pembelian, Silahkan coba dengan data yang sesuai');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $order = Order::find($id);
        return view('order.kasir.print', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
        public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }

    public function downloadPDF($id)
    {
        // get data yang akan ditampilkan di pdf
        // data yang dikirim ke pdf wajib bertipe array
        $order = Order::find($id)->toArray();

        // ketika data dipanggil di blade pdf, akan dipanggil dengan $ apa
        view()->share('order', $order);

        // lokasi dan nama blade yang akan di download ke pdf serta data yang akan ditampilkan
        $pdf = PDF::loadView('order.kasir.download-pdf', $order);

        // ketika didownload nama filenya apa
        return $pdf->download('Bukti Pembelian.pdf');
    }

    public function search(Request $request)
    {
        $searchDate = $request->input('search');
        
        // Lakukan pencarian berdasarkan tanggal
        $orders = Order::whereDate('created_at', $searchDate)->simplePaginate(5);
    
        return view('order.kasir.index', compact('orders'));
    }

    public function downloadPDFAdmin($id)
    {
        // get data yang akan ditampilkan di pdf
        // data yang dikirim ke pdf wajib bertipe array
        $order = Order::find($id)->toArray();

        // ketika data dipanggil di blade pdf, akan dipanggil dengan $ apa
        view()->share('order', $order);

        // lokasi dan nama blade yang akan di download ke pdf serta data yang akan ditampilkan
        $pdf = PDF::loadView('order.admin.download-pdf', $order);

        // ketika didownload nama filenya apa
        return $pdf->download('Bukti Pembelian.pdf');
    }

    public function searchAdmin(Request $request)
    {
        $searchDate = $request->input('search');
        
        // Lakukan pencarian berdasarkan tanggal
        $orders = Order::whereDate('created_at', $searchDate)->simplePaginate(5);
    
        return view('order.admin.index', compact('orders'));
    }
    
    public function downloadExcel()
    {
        // nama file excel ketita didownload
        $file_name = 'Data seluruh Pembelian.xlsx';
        // panggil logic exportsnya
        return Excel::download(new OrderExport, $file_name);
    }

}
