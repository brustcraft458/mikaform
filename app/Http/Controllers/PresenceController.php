<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Models\Dump;
use App\Models\Presence;
use App\Models\Section;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class PresenceController extends Controller
{
    function webPresence($uuid) {
        $presence = Presence::where('uuid', $uuid)->first();
        if (!$presence) {
            return redirect()->route('landing');
        }

        return view('presence.qrgenerate', ['type' => 'presence', 'uuid' => $uuid]);
    }

    function webScanner($uuid) {
        $template = Template::where('uuid', $uuid)->first();
        if (!$template) {
            return redirect()->route('form_template');
        }

        // Last Presence Created
        $startOfDay = today()->startOfDay(); // 00:00:00
        $endOfDay = today()->endOfDay(); // 23:59:59

        $last = Presence::whereBetween('created_at', [$startOfDay, $endOfDay])->where('id_template', $template['id'])->pluck('created_at')->first();

        return view('presence.qrscanner', ['type' => 'presence', 'last' => $last, 'uuid' => $uuid]);
    }

    function handlePresence(Request $request, $uuid) {
        // Proccess
        if ($request->has('presence_generate')) {
            return $this->generatePresence($uuid);
        } elseif ($request->has('presence_input')) {
            return $this->inputPresence($request, $uuid);
        }

        return response(400);
    }

    function generatePresence($uuid) {
        // Get Template
        $template = Template::where('uuid', $uuid)->first();
        if (!$template) {
            session()->flash('action_message', 'presence_generate_notfound_failed');
            session()->flash('action_data', ['template_uuid' => $uuid, 'state' => 'template_not_found']);
            return redirect()->route('presence_scanner', ['uuid' => $uuid]);
        }

        // Init
        $message_list = [];

        // Dump List
        $dump_list = Dump::where('id_template', $template['id'])->get();

        foreach ($dump_list as $dump) {
            // Get Phone
            $section = Section::where('type', 'phone')->where('id_template', $template['id'])->first();
            if (!$section) {continue;} 
            $phone = Data::where('id_dump', $dump['id'])->where('id_section', $section['id'])->pluck('value')->first();

            // Create Data
            $presence = Presence::create([
                'id_template' => $template['id'],
                'id_dump' => $dump['id']
            ]);

            // Message
            $urlpath = url('/presence' . '/' . $presence['uuid']);
            $text = "Silahkan Melakukan Presensi\n*'" . $template['title'] . "'*\nmenggunakan link dibawah ini\n" . $urlpath;
            array_push($message_list, [
                'phone' => $phone,
                'text' => $text
            ]);
        }

        // Send All Message
        $waurl = env('WA_GATEWAY_URL') . '/api' . '/messages';
        $response = Http::withHeaders([
            'Authorization' => env('WA_GATEWAY_KEY')
        ])->post($waurl, [
            'data_list' => $message_list
        ]);

        return redirect()->route('presence_scanner', ['uuid' => $uuid]);
    }

    function inputPresence($request, $uuid) {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:presence',
            'uuid' => 'required|uuid'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'presence_input_validation_failed',
                'data' => $validator->errors()
            ], 400);
        }

        $input = $validator->validated();

        // Get Template
        $template = Template::where('uuid', $uuid)->first();
        if (!$template) {
            return response()->json([
                'message' => 'presence_input_notfound_failed',
                'data' => ['template_uuid' => $uuid, 'state' => 'template_not_found']
            ], 404);
        }

        // Get Presence
        $presence = Presence::where('uuid', $input['uuid'])->where('id_template', $template['id'])->first();
        if (!$presence) {
            return response()->json([
                'message' => 'presence_input_notfound_failed',
                'data' => ['presence_uuid' => $input['uuid'], 'state' => 'presence_not_found']
            ], 404);
        }

        if (!is_null($presence['presence_at'])) {
            return response()->json([
                'message' => 'presence_input_exists_failed',
                'data' => ['presence_uuid' => $input['uuid'], 'state' => 'presence_exists']
            ], 409);
        }

        $presence->update(['presence_at' => now()]);

        return response()->json(['message' => 'presence_input_success']);
    }
}
