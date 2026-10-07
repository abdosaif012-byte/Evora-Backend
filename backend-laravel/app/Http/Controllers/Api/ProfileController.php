<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function show()
    {
        return response()->json(auth()->user());
    }
}