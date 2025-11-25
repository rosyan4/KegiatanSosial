<?php

namespace App\Http\Controllers;

use App\Models\Documentation;
use Illuminate\Http\Request;

class DocumentationController extends Controller
{
    public function index()
    {
        $docs = Documentation::with('activity')
            ->latest()
            ->paginate(10);

        return view('documentations.index', compact('docs'));
    }

    public function show(Documentation $documentation)
    {
        $documentation->incrementViewCount();

        return view('documentations.show', compact('documentation'));
    }
}
