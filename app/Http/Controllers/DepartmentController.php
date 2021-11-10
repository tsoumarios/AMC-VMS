<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    /**
     * Returns view of all visitors
     *
     * @return void
     */
    public function index() {
        

        return view('departments.index');
    }
}
