<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Models\Dump;
use App\Models\Section;
use Illuminate\Http\Request;

class FormDataController extends Controller
{
    function index($uuid) {
        $result = Dump::allCombinedData($uuid);

        return view('form.data', [
            'label_list' => $result['label_list'],
            'dump_list' => $result['dump_list']
        ]);
    }
}
