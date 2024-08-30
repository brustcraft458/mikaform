<?php

namespace App\Http\Controllers;

use App\Models\Dump;
use Illuminate\Http\Request;

class PresenceController extends Controller
{
    function webPresence($uuid) {
        $dump = Dump::where('uuid', $uuid)->first();
        if (!$dump) {
            return redirect()->route('landing');
        }

        return view('presence.qrgenerate', ['type' => 'presence', 'uuid' => $uuid]);
    }
}
