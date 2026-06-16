<?php

namespace App\Http\Controllers;

use App\Models\PaketBundling;
use Illuminate\Http\Request;

class SpkController extends Controller
{
    public function index(Request $request)
    {
        $pakets = PaketBundling::with(['penilaian', 'acaras'])
            ->whereHas('penilaian', function ($q) {
                $q->whereNotNull('ranking');
            })
            ->get()
            ->sortBy('penilaian.ranking');

        return view('frontend.rekomendasi', compact('pakets'));
    }
}
