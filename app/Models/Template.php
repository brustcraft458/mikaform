<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class Template extends Model
{
    use HasFactory;

    protected $table = 'tbl_template';

    protected $fillable = [
        'title',
        'visibility',
        'total_viewed',
        'total_respondent',
        'id_user'
    ];

    /**
     * Before creating a record.
     */
    protected static function booted()
    {
        static::creating(function ($dump) {
            // Generate a UUID
            if (empty($dump->uuid)) {
                $dump->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Functions
     */
    static function allSection($uuid_template) {
        // Get Template
        $templateQuery = self::where('uuid', $uuid_template);

        // Check Admin
        if (!Auth::check() || !in_array(Auth::user()->role, ['admin', 'super_admin'])) {
            $templateQuery->where('visibility', 'public');
        }

        // Fetch the template
        $template = $templateQuery->first();
        if (!$template) {
            return null;
        }

        // Fetch sections
        $sections = Section::where('id_template', $template->id)->get();

        // Combine data
        $template->setAttribute('section_list', $sections);

        return $template;
    }

    static function allDumpData($uuid_template) {
        // Get Template
        $template = self::where('uuid', $uuid_template)->first();
        if (!$template) {
            return null;
        }

        // Init
        $labelList = [];
        $sectionCache = [];
        $dumpList = [];

        // Function to insert data into lists
        $insertData = function(&$dataList, &$labelList, $data) {
            if (!in_array($data['label'], $labelList)) {
                $labelList[] = $data['label'];
            }
            $dataList[] = $data;
        };

        // Fetch dumps with chunking
        Dump::where('id_template', $template->id)->chunk(100, function($dumps) use (&$labelList, &$sectionCache, &$dumpList, $insertData) {
            foreach ($dumps as $dump) {
                $dataListDB = Data::select('id', 'id_section', 'value')->where('id_dump', $dump->id)->get();
                $dataList = [];
                
                // Process data
                foreach ($dataListDB as $data) {
                    $sectionId = $data->id_section;
                    
                    // Cache Section data
                    if (!isset($sectionCache[$sectionId])) {
                        $sectionCache[$sectionId] = Section::select('label', 'type')->find($sectionId);
                    }
                    
                    $section = $sectionCache[$sectionId];
                    if ($section) {
                        // Merge data
                        $nData = [
                            'label' => $section->label,
                            'type' => $section->type,
                            'value' => $data->value
                        ];
                        $insertData($dataList, $labelList, $nData);
                    }
                }

                // Add presence data
                $presence = Presence::where('id_dump', $dump->id)->whereNotNull('presence_at');
                $nData = [
                    'label' => 'Presensi',
                    'type' => 'presence',
                    'value' => $presence->count(),
                    'presence_list' => $presence->pluck('presence_at')->toArray()
                ];
                $insertData($dataList, $labelList, $nData);

                // Assign data list to dump
                $dump->setAttribute('data_list', $dataList);

                // Add to dump list
                $dumpList[] = $dump;
            }
        });

        // Combine data
        $template->setAttribute('label_list', $labelList);
        $template->setAttribute('dump_list', $dumpList);

        return $template;
    }
}
