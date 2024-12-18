<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index()
    {
        return inertia('Guest/Welcome');
    }

    public function about()
    {
        return inertia('Guest/About');
    }
}
