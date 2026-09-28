<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['kode' => 'KTG001', 'nama' => 'Obat'],
            ['kode' => 'KTG002', 'nama' => 'Alkes'],
            ['kode' => 'KTG003', 'nama' => 'Matkes'],
            ['kode' => 'KTG004', 'nama' => 'Umum'],
            ['kode' => 'KTG005', 'nama' => 'ATK'],
        ];

        foreach ($data as $row) {
            $kategori = new Kategori;
            $kategori->kode = $row['kode'];
            $kategori->nama = $row['nama'];
            $kategori->save();
        }
    }
}
