<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Models\Dump;
use App\Models\Section;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FormDataController extends Controller
{
    function webData($uuid) {
        $template = Template::allDumpData($uuid);

        if (!$template['label_list']) {
            return view('form.data', [
                'label_list' => [],
                'dump_list' => []
            ]);
        }

        return view('form.data', [
            'uuid' => $template['uuid'],
            'label_list' => $template['label_list'],
            'dump_list' => $template['dump_list']
        ]);
    }

    function webShare($uuid) {
        $template = Template::allSection($uuid);

        if (!$template) {
            return view('form.notfound', [
                'uuid' => $uuid
            ]);
        }

        // Increment view
        if (empty(session('action_message'))) {
            $template->increment('total_viewed');
        }

        return view('form.share', [
            'uuid' => $uuid,
            'title' => $template['title'],
            'section_list' => $template['section_list'],
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

        // Update Count
        $template->increment('total_respondent');

        // Get Sections
        foreach ($input['section_list'] as $section) {
            $sectionDB = Section::find($section['id'])->where('id_template', $template['id'])->first();
            if (!$sectionDB) {
                session()->flash('action_message', 'form_input_failed');
                session()->flash('action_data', ['form_uuid' => $uuid, 'section_id' => $section['id'], 'state' => 'section_not_found']);
                return redirect()->route('form_share', ['uuid'=> $uuid]);
            }
        };

        // New Dump
        $dump = Dump::create(['id_template' => $template['id']]);

        // Input Sections
        foreach ($input['section_list'] as &$section) {
            if ($section['type'] == 'number') {
                $section['value'] = formatPhone($section['value']);
            } elseif ($section['type'] == 'file') {
                // Has File
                $fileLabel = $section['value'];
                $file = $request->file($fileLabel);

                // Check
                $validator = Validator::make([$fileLabel => $file], [
                    $fileLabel => 'required|file|mimes:jpg,png,pdf,docx|max:2048'
                ]);

                if ($validator->fails()) {
                    session()->flash('action_message', 'form_input_failed');
                    session()->flash('action_data', $validator->errors()->toJson());
                    return redirect()->route('form_share', ['uuid'=> $uuid]);
                }

                // File Name
                $fileUUID = Str::uuid()->toString();
                $fileExt = $file->getClientOriginalExtension(); // ekstensi file
                $fileName = $fileUUID . '.' . $fileExt;

                // Save File
                $filePath = $file->storeAs('uploads', $fileName, 'public');
                $section['value'] = $filePath;
            }

            // Input
            Data::create([
                'value' => $section['value'],
                'id_section' => $section['id'],
                'id_dump' => $dump['id']
            ]);
        };

        session()->flash('action_message', 'form_input_success');
        return redirect()->route('form_share', ['uuid'=> $uuid]);
    }

    function handleData(Request $request, $uuid) {
        // Proccess
        if ($request->has('export') && $request->has('selected_ids')) {
            if ($request->input('export') === 'csv') {
                return $this->exportCsv($request, $uuid);
            }
        }

        return response('', 400);
    }

    public function exportCsv($request, $uuid)
    {
        $selectedIds = json_decode($request->input('selected_ids'), true);

        // Template
        $template = Template::allDumpData($uuid, $selectedIds);
        
        // Init
        $dumps = $template->dump_list;
        $filename = Str::slug($template['title'], '_') . "_data.csv";

        // Generate CSV
        $response = new StreamedResponse(function () use ($template, $dumps) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, array_merge(['NO'], $template['label_list']));

            // Loop dumps
            $count = 0;
            foreach ($dumps as $dump) {
                // Init
                $count++;
                $row = [$count];
            
                // Collect 'value'
                $row = array_merge($row, array_column($dump->data_list, 'value'));
            
                // Write CSV
                fputcsv($handle, $row);
            }


            fclose($handle);
        });

        // Headers response
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }
}
