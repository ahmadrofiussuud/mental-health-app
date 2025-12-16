<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class MoodDetectionController extends Controller
{
    /**
     * Display the mood detection page.
     */
    public function index(): View
    {
        return view('pages.mood-detection');
    }
}
