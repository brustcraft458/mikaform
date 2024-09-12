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
