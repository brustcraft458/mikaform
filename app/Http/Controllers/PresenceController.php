<?php

namespace App\Http\Controllers;

use App\Models\Dump;
use App\Models\Template;
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

    function webScanner($uuid) {
        $template = Template::where('uuid', $uuid)->first();
        if (!$template) {
            return redirect()->route('form_template');
        }

        return view('presence.qrscanner');
    }
}
