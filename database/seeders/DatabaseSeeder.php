<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // menambahkan data ke table di database tanpa melalui input form (biasanya untuk data-data default / bawaaan)
        // "fillable" => "isiannya"
        User::create([
            "name" => "Grizzly2",
            "email" => "grizzly2@gmail.com",
            // hash : enkripsi agar password tersimpan berisi teks acak agar tidak bisa diprediksi / dibaca orang lain
            // selain hash ada juga bcrypt
            "password" => Hash::make("ikbal05"),
            "role" => "admin"
        ]);
    }
}
