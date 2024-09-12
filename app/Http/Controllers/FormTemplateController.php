<?php

namespace App\Http\Controllers;

use App\Models\Dump;
use App\Models\Section;
use App\Models\Template;
use App\Models\User;
use Illuminate\Http\Request;
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
            session()->flash('action_message', 'form_option_failed');
            session()->flash('action_data', $validator->errors());
            return redirect()->route('form_template');
        }

        $input = $validator->validated();

        // Template
        $template = Template::where('uuid', $uuid)->first();
        if (!$template) {
            session()->flash('action_message', 'form_option_failed');
            session()->flash('action_data', $validator->errors());
            return redirect()->route('form_template');
        }

        // Update
        $template->update(['visibility' => $input['visibility']]);

        session()->flash('action_message', 'form_option_success');
        session()->flash('action_data', $template->toArray());
        return redirect()->route('form_template');
    }
}
