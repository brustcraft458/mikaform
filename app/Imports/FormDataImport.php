<?php

namespace App\Imports;

use App\Models\Data;
use App\Models\Dump;
use App\Models\Section;
use App\Models\Template;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FormDataImport implements ToModel, WithHeadingRow
{
    /**
     * Process each row from the Excel file.
     */
    public function model(array $row)
    {
        // Cari template berdasarkan nama
        $template = Template::where('title', $row['template'])->first();
        if (!$template) {
            return null; // Lewati jika template tidak ditemukan
        }

        // Cari atau buat Dump baru
        $dump = Dump::create(['id_template' => $template['id']]);

        // All Section
        $sectionAll = Section::where('id_template', $template['id'])->get()->toArray(); 
        //return var_dump($row);

        $dump_log = [];
        
        foreach ($sectionAll as $section) {
            $key = strtolower(str_replace(' ', '_', $section['label']));
            if ($key == 'id') {continue;}

            $value = $row[$key];

            array_push($dump_log, ['key' => $key, 'value' => $value]);

            // Insert
            Data::create([
                'value'      => $value,
                'id_section' => $section['id'],
                'id_dump'    => $dump['id']
            ]);
        }

        return var_dump($dump_log);
    }
}
