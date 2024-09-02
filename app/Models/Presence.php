<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Presence extends Model
{
    use HasFactory;

    protected $table = 'tbl_presences';

    protected $fillable = [
        'presence_at',
        'id_template',
        'id_dump'
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
}
