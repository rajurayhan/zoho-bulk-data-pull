<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fields;

class FieldController extends Controller
{
    public function index()
    {
        $fields = Fields::all();
        return view('fields.index', compact('fields'));
    }
}
