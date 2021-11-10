<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visitor;

class VisitorController extends Controller
{
    /**
     * Returns view of all visitors
     *
     * @return void
     */
    public function index() {
        $visitors = Visitor::all();
     
        return view('visitors.index', compact(
            'visitors'
        ));
    }
}
