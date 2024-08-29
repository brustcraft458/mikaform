<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Models\Dump;
use App\Models\Section;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FormDataController extends Controller
{
    function webData($uuid) {
        $result = Dump::allCombinedData($uuid);

        return view('form.data', [
            'label_list' => $result['label_list'],
            'dump_list' => $result['dump_list']
        ]);
    }

    function webShare($uuid) {
        $result = Template::allSection($uuid);

        return view('form.share', [
            'uuid' => $uuid,
            'title' => $result['title'],
            'section_list' => $result['section_list'],
        ]);
    }

    function userInput(Request $request, $uuid) {
        $jsonData = $request->input('json-data');
        $jsonData = json_decode($jsonData, true);
        
        $validator = Validator::make($jsonData, [
            'title' => 'required|string|max:255',
            'section_list' => 'required|array',
            'section_list.*.id' => 'required|integer',
            'section_list.*.label' => 'required|string|max:255',
            'section_list.*.type' => 'required|string|max:255',
            'section_list.*.value' => 'required|string|max:255'
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            session()->flash('action_message', 'form_input_failed');
            session()->flash('action_data', $validator->errors()->toJson());
            return redirect()->route('form_share', ['uuid'=> $uuid]);
        }

        $input = $validator->validated();

        // Template
        $template = Template::where('uuid', $uuid)->first();
        if (!$template) {
            session()->flash('action_message', 'form_input_failed');
            session()->flash('action_data', ['form_uuid' => $uuid, 'state' => 'form_not_found']);
            return redirect()->route('form_share', ['uuid'=> $uuid]);
        }

        // Get Sections
        foreach ($input['section_list'] as $section) {
            $sectionDB = Section::find($section['id'])->where('id_template', $template['id']);
            if (!$sectionDB) {
                session()->flash('action_message', 'form_input_failed');
                session()->flash('action_data', ['form_uuid' => $uuid, 'section_id' => $section['id'], 'state' => 'section_not_found']);
                return redirect()->route('form_share', ['uuid'=> $uuid]);
            }
        };

        // New Dump
        $dump = Dump::create(['id_template' => $template['id']]);

        // Input Sections
        foreach ($input['section_list'] as $section) {
            Data::create([
                'value' => $section['value'],
                'id_section' => $section['id'],
                'id_dump' => $dump['id']
            ]);
        };

        session()->flash('action_message', 'form_input_success');
        return redirect()->route('form_share', ['uuid'=> $uuid]);
    }
}
