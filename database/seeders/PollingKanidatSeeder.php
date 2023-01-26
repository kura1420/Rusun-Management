<?php

namespace Database\Seeders;

use App\Models\PollingKanidat;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PollingKanidatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $grup = [
            '0d61bbfdca316ec9be828548c188189b',
            '0b28854b69b94642b903122a6593eb23',
            '0d61bbfdca316ec9be828548c188189b',
            '0b28854b69b94642b903122a6593eb23',
        ];

        for ($i=1; $i <= rand(30, 50); $i++) { 
            $rusunPemilik = \App\Models\RusunPemilik::where('rusun_id', '3a1ed721-dfe6-4df1-995c-ea775f739e20')->inRandomOrder()->first();

            PollingKanidat::updateOrCreate(
                [
                    'pemilik_penghuni_memilih' => $rusunPemilik->pemilik_id,
                    'program_id' => '66f9e736-7ef4-459a-892a-6fea88e9ae43',
                    'rusun_id' => '3a1ed721-dfe6-4df1-995c-ea775f739e20',
                ],
                [
                    'waktu' => Carbon::now(),
                    'apakah_pemilik' => 1,
                    'grup_id' => $grup[rand(0, 3)],
                ]
            );
        }

        for ($i=1; $i <= rand(30, 50); $i++) { 
            $rusunPenghuni = \App\Models\RusunPenghuni::where('rusun_id', '3a1ed721-dfe6-4df1-995c-ea775f739e20')->inRandomOrder()->first();

            PollingKanidat::updateOrCreate(
                [
                    'pemilik_penghuni_memilih' => $rusunPenghuni->id,
                    'program_id' => '66f9e736-7ef4-459a-892a-6fea88e9ae43',
                    'rusun_id' => '3a1ed721-dfe6-4df1-995c-ea775f739e20',
                ],
                [
                    'waktu' => Carbon::now(),
                    'apakah_pemilik' => 0,
                    'grup_id' => $grup[rand(0, 3)],
                ]
            );
        }
    }
}
