<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modules;

class ModuleController extends Controller
{
    public function index()
    {
        $modules = Modules::all();
        return view('modules.index', compact('modules'));
    }
}
