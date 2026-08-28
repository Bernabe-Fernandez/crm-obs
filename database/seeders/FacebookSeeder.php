<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacebookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('vt_facebook_page')->insert([
            'nombre' => 'Omnibandas MX',
            'facebook_id' => '639677512555439',
            'token' => 'EAAMbfY3jKiwBSKDm6vzsplxniqQNIaSSdMsaKoAztZCLvv5oZCjkBRJTuwso1sjsYRPZBYeDk2OfKlrO96rLHIyyRe1aAWN99ZCas8IVpSXP8k6q7dnrI5mceii7PczdbOjW8lNDdXh5zlmxxWMrP6s3sEkUKExkSbCk1ZAf7mkVVTGI9tlbXOSScaCdW7DRgP8JXKVnl'
        ]);

        DB::table('vt_facebook_forms')->insert([
            'page_id' => 1,
            'form_id' => '1198054145629534',
            'nombre' => 'FORMULARIO DE ATENCIÓN OMNIBANDAS',
        ]);
    }
}

