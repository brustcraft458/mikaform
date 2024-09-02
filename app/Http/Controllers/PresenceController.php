<?php

namespace App\Http\Controllers;

use App\Models\Dump;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

        return view('presence.qrscanner', ['type' => 'presence', 'uuid' => $uuid]);
    }

    function inputPresence(Request $request, $uuid) {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:presence',
            'uuid' => 'required|uuid'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 400);
        }

        $input = $validator->validated();

        // Get Template
        $template = Template::where('uuid', $uuid)->first();
        if (!$template) {
            return response()->json([
                'uuid' => $uuid,
                'message' => 'Template Not Foud'
            ], 400);
        }

        // Get Dump
        $dump = Dump::where('uuid', $input['uuid'])->where('id_template', $template['id'])->first();
        if (!$dump) {
            return response()->json([
                'uuid' => $uuid,
                'message' => 'Dump Not Foud'
            ], 400);
        }
        $dump->update(['presence_at' => now()]);

        return response()->json(['yes' => 'sir', 'data' => $dump]);
    }
}
