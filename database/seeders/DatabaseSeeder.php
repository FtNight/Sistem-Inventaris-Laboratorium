<?php

namespace Database\Seeders;

use App\Models\Asset;
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
        // Buat akun Kepala Lab
        User::firstOrCreate(
            ['email' => 'kepala@lab.com'],
            [
                'name'     => 'Kepala Laboratorium',
                'password' => Hash::make('password'),
                'role'     => 'kepala_lab',
            ]
        );

        // Buat akun Laboran
        User::firstOrCreate(
            ['email' => 'laboran@lab.com'],
            [
                'name'     => 'Ahmad Laboran',
                'password' => Hash::make('password'),
                'role'     => 'laboran',
            ]
        );

        // Seed data aset contoh
        $assets = [
            [
                'kode_barang'   => 'ELK-001',
                'nama_barang'   => 'Osiloskop Digital',
                'kategori'      => 'Elektronika',
                'stock_good'    => 5,
                'stock_damaged' => 1,
                'keterangan'    => 'Digunakan untuk pengukuran sinyal elektronik',
            ],
            [
                'kode_barang'   => 'ELK-002',
                'nama_barang'   => 'Multimeter Digital',
                'kategori'      => 'Elektronika',
                'stock_good'    => 12,
                'stock_damaged' => 2,
                'keterangan'    => 'Alat ukur tegangan, arus, dan resistansi',
            ],
            [
                'kode_barang'   => 'KOM-001',
                'nama_barang'   => 'Komputer Desktop',
                'kategori'      => 'Komputer',
                'stock_good'    => 20,
                'stock_damaged' => 3,
                'keterangan'    => 'PC untuk praktikum mahasiswa',
            ],
            [
                'kode_barang'   => 'KOM-002',
                'nama_barang'   => 'Laptop Lenovo',
                'kategori'      => 'Komputer',
                'stock_good'    => 8,
                'stock_damaged' => 0,
                'keterangan'    => 'Laptop untuk asisten laboratorium',
            ],
            [
                'kode_barang'   => 'JAR-001',
                'nama_barang'   => 'Gelas Beaker 500ml',
                'kategori'      => 'Peralatan Kimia',
                'stock_good'    => 30,
                'stock_damaged' => 5,
                'keterangan'    => 'Gelas ukur untuk praktikum kimia',
            ],
            [
                'kode_barang'   => 'JAR-002',
                'nama_barang'   => 'Tabung Reaksi',
                'kategori'      => 'Peralatan Kimia',
                'stock_good'    => 50,
                'stock_damaged' => 8,
                'keterangan'    => 'Tabung kaca untuk reaksi kimia',
            ],
            [
                'kode_barang'   => 'NET-001',
                'nama_barang'   => 'Cisco Switch 24-Port',
                'kategori'      => 'Jaringan',
                'stock_good'    => 4,
                'stock_damaged' => 1,
                'keterangan'    => 'Switch jaringan untuk lab komputer',
            ],
            [
                'kode_barang'   => 'FRN-001',
                'nama_barang'   => 'Kursi Lab Ergonomis',
                'kategori'      => 'Furnitur',
                'stock_good'    => 40,
                'stock_damaged' => 2,
                'keterangan'    => 'Kursi untuk mahasiswa praktikum',
            ],
        ];

        foreach ($assets as $asset) {
            Asset::firstOrCreate(
                ['kode_barang' => $asset['kode_barang']],
                $asset
            );
        }
    }
}
