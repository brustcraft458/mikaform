<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dump extends Model
{
    use HasFactory;

    protected $table = 'tbl_dumps';

    protected $fillable = [
        'id_template'
    ];

    /**
     * Functions
     */
    static function allCombinedData($uuid_template) {
        // Get Template
        $template = Template::where('uuid', $uuid_template)->first();
        if (!$template) {
            return [
                'label_list' => [],
                'dump_list' => []
            ];
        }

        // Init
        $label_list = []; 
        $section_cache = [];
        $dump_list = [];

        // Function
        function insertData(&$dataof_list, &$labelof_list, $data) {
            // Check label_list
            if (!in_array($data['label'], $labelof_list)) {
                array_push($labelof_list, $data['label']);
            }

            array_push($dataof_list, $data);
        }

        // Fetch dumps with chunking
        Dump::where('id_template', $template['id'])->chunk(100, function($dumps) use (&$label_list, &$section_cache, &$dump_list) {
            foreach ($dumps as $dump) {
                $data_listDB = Data::select('id', 'id_section', 'value')->where('id_dump', $dump['id'])->get();
                $data_list = [];
            
                // Custom Data
                foreach ($data_listDB as $data) {
                    $section_id = $data['id_section'];
                
                    // Check cached
                    if (!isset($section_cache[$section_id])) {
                        $section_cache[$section_id] = Section::select('label', 'type')->find($section_id);
                    }
                
                    $section = $section_cache[$section_id];

                    // Merge
                    $nData = [
                        'label' => $section['label'],
                        'type' => $section['type'],
                        'value' => $data['value']
                    ];

                    insertData($data_list, $label_list, $nData);    
                }

                // Presence
                $presence = Presence::where('id_dump', $dump['id'])->whereNotNull('presence_at');
                $nData = [
                    'label' => 'Presensi',
                    'type' => 'presence',
                    'value' => $presence->count(),
                    'data_list' => $presence->pluck('presence_at')->toArray()
                ];

                insertData($data_list, $label_list, $nData); 
            
                // Assign
                $dump->setAttribute('data_list', $data_list);
            
                // Push
                $dump_list[] = $dump;
            }
        });

        return [
            'label_list' => $label_list,
            'dump_list' => $dump_list
        ];
    }

    static function allPhoneData($uuid_template) {
        // Get Template
        $template = Template::where('uuid', $uuid_template)->first();
        if (!$template) {
            return [
                'phone_list' => []
            ];
        }
        $template = $template->toArray();

        // Get Phone
        $section = Section::where('type', 'phone')->where('id_template', $template['id'])->first();
        $phone_list = Data::select('value')->where('id_section', $section['id'])->pluck('value')->toArray();

        return [
            'phone_list' => $phone_list
        ];
    }
}
