<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Models\Dump;
use App\Models\Section;
use App\Models\Template;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FormTemplateController extends Controller
{
    function index() {
        //$form_list = Template::with('section_list')->get()->toArray();
        $form_list = Template::all()->toArray();

        return view('form.template', [
            'form_list' => $form_list
        ]);
    }

    function handleForm(Request $request) {
        // Proccess
        if ($request->has('form_add')) {
            return $this->addTemplate($request);
        } elseif ($request->has('form_option')) {
            $uuid = $request->get('uuid');

            if ($request->has('visibility')) {
                return $this->changeVisibility($request, $uuid);
            } elseif ($request->has('message')) {
                return $this->broadcastMessage($request, $uuid);
            }
        }

        return response('', 400);
    }

    function addTemplate($request) {
        $jsonData = $request->input('json-data');
        $jsonData = json_decode($jsonData, true);

        $validator = Validator::make($jsonData, [
            'title' => 'required|string|max:255',
            'section_list' => 'required|array',
            'section_list.*.label' => 'required|string|max:255',
            'section_list.*.type' => 'required|string|max:255'
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            session()->flash('action_message', 'form_create_failed');
            session()->flash('action_data', $validator->errors());
            return redirect()->route('form_template');
        }

        $input = $validator->validated();

        // Self User
        $user = Auth::user();
        $input['id_user'] = $user['id'];

        // New Template
        $template = new Template($input);
        $template->save();

        // New Sections
        foreach ($input['section_list'] as $section) {
            Section::create([
                'label' => $section['label'],
                'type' => $section['type'],
                'id_template' => $template['id']
            ]);
        };

        // Respone
        session()->flash('action_message', 'form_create_success');
        session()->flash('action_data', json_encode($jsonData));
        return redirect()->route('form_template');
    }

    function changeVisibility($request, $uuid) {
        $validator = Validator::make($request->all(), [
            'visibility' => 'required|string|in:public,private',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            session()->flash('action_message', 'form_option_visibility_failed');
            session()->flash('action_data', $validator->errors());
            return redirect()->route('form_template');
        }

        $input = $validator->validated();

        // Template
        $template = Template::where('uuid', $uuid)->first();
        if (!$template) {
            session()->flash('action_message', 'form_option_visibility_failed');
            session()->flash('action_data', $validator->errors());
            return redirect()->route('form_template');
        }

        // Update
        $template->update(['visibility' => $input['visibility']]);

        session()->flash('action_message', 'form_option_visibility_success');
        session()->flash('action_data',  ['visibility' => $input['visibility']]);
        return redirect()->route('form_template');
    }

    function broadcastMessage($request, $uuid) {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            session()->flash('action_message', 'form_option_message_failed');
            session()->flash('action_data', $validator->errors());
            return redirect()->route('form_template');
        }

        $input = $validator->validated();

        // Template
        $template = Template::where('uuid', $uuid)->first();
        if (!$template) {
            session()->flash('action_message', 'form_option_message_failed');
            session()->flash('action_data', $validator->errors());
            return redirect()->route('form_template');
        }

        // Dump List
        $dump_list = Dump::where('id_template', $template['id'])->get();

        foreach ($dump_list as $dump) {
            // Get Phone
            $section = Section::where('type', 'phone')->where('id_template', $template['id'])->first();
            if (!$section) {continue;} 
            $phone = Data::where('id_dump', $dump['id'])->where('id_section', $section['id'])->pluck('value')->first();

            // Message
            $text = "Pesan dari\n*'" . $template['title'] . "'*\n\n" . $input['message'];
            
            // Send Message
            $waurl = env('WA_GATEWAY_URL') . '/api' . '/messages';
            $response = Http::withHeaders([
                'Authorization' => env('WA_GATEWAY_KEY')
            ])->post($waurl, [
                'phone' => $phone,
                'text' => $text
            ]);
        }

        // End
        session()->flash('action_message', 'form_option_message_success');
        session()->flash('action_data',  ['count' => count($dump_list)]);
        return redirect()->route('form_template');
    }
}
