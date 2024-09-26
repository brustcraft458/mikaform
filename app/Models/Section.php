<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $table = 'tbl_sections';

    protected $fillable = [
        'label',
        'type',
        'image',
        'id_template'
    ];

    public function data()
    {
        return $this->hasMany(Data::class, 'id_section');
    }
}
