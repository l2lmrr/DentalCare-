<?php

namespace App\Http\Controllers;

use App\Models\Dentist;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $dentists = Dentist::with('user')
            ->whereHas('user', function($query) {
                $query->where('role', 'praticien');
            })
            ->get();
            
        return view('home', compact('dentists'));
    }
}