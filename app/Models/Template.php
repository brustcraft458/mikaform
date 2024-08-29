<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
        $template = Template::where('uuid', $uuid_template)->first();
        if (!$template) {
            return [
                'title' => '',
                'section_list' => []
            ];
        }
        $template = $template->toArray();

        // Section
        $section_list = Section::where('id_template', $template['id'])->get()->toArray();

        return [
            'title' => $template['title'],
            'section_list' => $section_list
        ];
    }
}
