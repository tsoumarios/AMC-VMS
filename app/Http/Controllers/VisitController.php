<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Visit;
use App\Models\User;

class VisitController extends Controller
{    
    /**
     * Returns view of all visits
     *
     * @return void
     */
    public function index() {
        $visits = Visit::all();
        $users = User::all()->where('superuser', false);
     
        return view('dashboard', compact(
            'visits', 'users'
        ));
    }

}
