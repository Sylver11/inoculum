<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Patient;

class PatientController extends Controller
{
    /**
     * Show the profile for a given user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */

    public function show($id)
    {
        return view('user.profile', [
            'user' => User::findOrFail($id)
        ]);
    }
}