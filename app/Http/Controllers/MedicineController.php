<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //proses ambil data
        $medicines = Medicine::orderBy('name', 'ASC')->simplePaginate(5);
        //manggil html yang ada di folder resources/views/medicine/index.blade.php
        //compact : mengirim data ke blade
        return view('medicine.data', compact('medicines'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('medicine.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validasi
        //'name_input => validasi1|validasi2
        $request->validate([
            'name' => 'required|min:3',
            'type' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
        ]);

        //simpan data ke db : 'nama_column' => $request->name_input
        Medicine::create([
            'name' => $request->name,
            'type' => $request->type,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        //abis simpen, arahin ke halaman mana
        return redirect()->back()->with('success', 'Berhasil menambahkan data obat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Medicine $medicine)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //mengambil data yang akan dimunculkan
        //find : mencari berdasarkan column id
        $medicine = Medicine::find($id);
        //atau $medicine = Medicine::where('id', $id)->first();

        return view('medicine.edit', compact('medicine'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|min:3',
            'type' => 'required',
            'price' => 'required|numeric',
        ]);

        Medicine::where('id', $id)->update([
        'name' => $request->name,
        'type' => $request->type,
        'price' => $request->price,
        ]);

        return redirect()->route('medicine.data')->with('success', 'Berhasil mengubah data obat!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Medicine::where('id', $id)->delete();
        return redirect()->back()->with('deleted', 'Berhasil menghapus data!');
    }

    public function stock()
    {
        $medicines = Medicine::orderBy('stock', 'ASC')->get();

        return view('medicine.stock', compact('medicines'));
    }

    public function stockEdit($id)
    {
        $medicine = Medicine::find($id);
        //mengembalikan bentuk json dikirim data yang diambil dengan response status code 200
        // response status code api : 
        //200 -> success/ok
        //400 an -> error kode/validasi input user
        //419 -> error token csrf
        //500 an -> error server hosting
        return response()->json($medicine, 200);
    }

    public function stockUpdate(Request $request, $id)
    {
        //validasi input
        $request->validate([
            'stock' => 'required|numeric',
        ], [
            'stock.required' => 'Input stok wajib diisi',
        ]);

        //ambil data sebelum update, untul dibandingkan
        $medicine = Medicine::find($id);

        if ($request->stock <= $medicine['stock']){
            //jika stok yang diinput <= stok sebelumnya, kirim format error
            return response()->json(["message" => "Stock yang diinput tidak boleh kurang dari stock sebelumnya"], 400);
        }else{
            $medicine->update(["stock" => $request->stock]);
            return response()->json("Berhasil", 200);
        }
    }

}
