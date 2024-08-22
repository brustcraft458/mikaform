<?php

namespace Database\Seeders;

use App\Models\Data;
use App\Models\Dump;
use App\Models\Section;
use App\Models\Template;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FormDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample
        $template = Template::where('title', 'Perancangan Pusat Data')->first();
        $dump_1 = Dump::create(['id_template' => $template['id']]);
        $dump_2 = Dump::create(['id_template' => $template['id']]);
        $section_1 = Section::where('id_template', $template['id'])->where('label', 'Judul:')->first();
        $section_2 = Section::where('id_template', $template['id'])->where('label', 'Deskripsi:')->first();

        // Create Data 1
        Data::firstOrCreate([
            'value' => 'Fungsi',
            'id_section' => $section_1['id'],
            'id_dump' => $dump_1['id']
        ]);

        Data::firstOrCreate([
            'value' => 'Sebagai pusatnya pembuatan formulir dan penyimpanan data - data',
            'id_section' => $section_2['id'],
            'id_dump' => $dump_1['id']
        ]);

        // Create Data 2
        Data::firstOrCreate([
            'value' => 'Target',
            'id_section' => $section_1['id'],
            'id_dump' => $dump_2['id']
        ]);

        Data::firstOrCreate([
            'value' => 'Untuk event - event yang akan datang',
            'id_section' => $section_2['id'],
            'id_dump' => $dump_2['id']
        ]);
    }
}
