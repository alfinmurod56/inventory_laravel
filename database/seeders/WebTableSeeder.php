<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WebTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tbl_web')->insert(
            [
                [
                    'web_nama' => 'Jazeera',
                    // 'web_logo' => 'alipay.svg',
                    // 'web_logo' => 'alipay.svg',
                    'web_logo' => url('/assets/default/barang/default.png'),
                    'web_deskripsi' => null,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                ],
            ]
        );
    }
}
