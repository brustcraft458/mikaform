<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Models\Dump;
use App\Models\Section;
use App\Models\Template;
use Illuminate\Http\Request;

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

    function userInput(Request $request) {
        $result = $request->all();

        return response()->json($result);
    }
}
