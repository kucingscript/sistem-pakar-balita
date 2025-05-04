<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Diagnosis;
use App\Models\Disease;
use App\Models\Symptom;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'userCount' => User::count(),
            'diseaseCount' => Disease::count(),
            'symptomCount' => Symptom::count(),
            'diagnosisCount' => Diagnosis::count(),
        ]);
    }
}
