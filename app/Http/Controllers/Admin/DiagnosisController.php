<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DiagnosisRequest;
use App\Models\Diagnosis;
use App\Models\Disease;
use App\Models\Symptom;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DiagnosisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $query = Diagnosis::orderby('updated_at', 'desc');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('symptoms', function ($item) {
                    $symptoms = json_decode($item->symptoms, true);

                    if (!is_array($symptoms)) {
                        return '-';
                    }
                    $codes = collect($symptoms)->pluck('code')->toArray();
                    $symptomNames = Symptom::whereIn('code', $codes)->pluck('description', 'code');

                    return collect($symptoms)->map(function ($s) use ($symptomNames) {
                        $code = $s['code'];
                        $name = $symptomNames[$code] ?? '-';
                        $confidence = isset($s['confidence']) ? $s['confidence'] : ($s['keyakinan'] ?? 0);
                        return "$code - $name (" . ($confidence * 100) . "%)";
                    })->implode('<br>');
                })
                ->addColumn('action', function ($item) {
                    $encryptedId = encrypt($item->id);
                    return '
                        <a class="block w-full px-2 py-1 mb-1 text-xs text-center text-white transition duration-500 bg-blue-700 border border-blue-700 rounded-md select-none ease hover:bg-blue-800 focus:outline-none focus:shadow-outline" 
                            href="' . route('admin.diagnoses.edit', $encryptedId) . '">
                            Edit
                        </a>
                        <form class="block w-full" onsubmit="return confirm(\'Are you sure want to delete?\');" -block" action="' . route('admin.diagnoses.destroy', $encryptedId) . '" method="POST">
                        <button class="w-full px-2 py-1 text-xs text-white transition duration-500 bg-red-500 border border-red-500 rounded-md select-none ease hover:bg-red-600 focus:outline-none focus:shadow-outline" >
                            Delete
                        </button>
                            ' . method_field('delete') . csrf_field() . '
                        </form>';
                })
                ->rawColumns(['action', 'symptoms'])
                ->make();
        }

        return view('admin.diagnoses.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $symptoms = Symptom::all();
        return view('admin.diagnoses.create', compact('symptoms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DiagnosisRequest $request)
    {
        $inputSymptoms = collect($request->symptoms);

        $symptomModels = Symptom::with('disease')->whereIn('code', $inputSymptoms->pluck('code'))->get();
        $allDiseaseSymptoms = Symptom::with('disease')->get()->groupBy('disease.name');

        $resultScores = [];

        foreach ($allDiseaseSymptoms as $diseaseName => $symptoms) {
            $score = 0;
            $maxScore = $symptoms->sum('weight');

            foreach ($symptoms as $symptom) {
                $userInput = $inputSymptoms->firstWhere('code', $symptom->code);
                if ($userInput) {
                    $score += $symptom->weight * $userInput['confidence'];
                }
            }

            $percentage = $maxScore > 0 ? ($score / $maxScore) * 100 : 0;
            $resultScores[$diseaseName] = $percentage;
        }

        arsort($resultScores);
        $diagnosedDisease = array_key_first($resultScores);
        $diagnosedPercentage = $resultScores[$diagnosedDisease];

        Diagnosis::create([
            'result_disease' => $diagnosedDisease,
            'result_percentage' => round($diagnosedPercentage, 2),
            'symptoms' => $inputSymptoms->toJson(),
        ]);

        return redirect()->route('admin.diagnoses.index')->with('success', 'Diagnosis created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $decryptedId = decrypt($id);

            $diagnosis = Diagnosis::findOrFail($decryptedId);
            $symptoms = Symptom::all();
            $selectedSymptoms = json_decode($diagnosis->symptoms, true);

            return view('admin.diagnoses.edit', compact('diagnosis', 'symptoms', 'selectedSymptoms'));
        } catch (DecryptException $e) {
            return redirect()->route('admin.diagnoses.index')->with('error', 'Diagnosis not found');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DiagnosisRequest $request, Diagnosis $diagnosis)
    {
        $inputSymptoms = collect($request->symptoms);
        $allDiseaseSymptoms = Symptom::with('disease')->get()->groupBy('disease.name');

        $resultScores = [];

        foreach ($allDiseaseSymptoms as $diseaseName => $symptoms) {
            $score = 0;
            $maxScore = $symptoms->sum('weight');

            foreach ($symptoms as $symptom) {
                $userInput = $inputSymptoms->firstWhere('code', $symptom->code);
                if ($userInput) {
                    $score += $symptom->weight * $userInput['confidence'];
                }
            }

            $percentage = $maxScore > 0 ? ($score / $maxScore) * 100 : 0;
            $resultScores[$diseaseName] = $percentage;
        }

        arsort($resultScores);
        $diagnosedDisease = array_key_first($resultScores);
        $diagnosedPercentage = $resultScores[$diagnosedDisease];

        $diagnosis->update([
            'result_disease' => $diagnosedDisease,
            'result_percentage' => round($diagnosedPercentage, 2),
            'symptoms' => $inputSymptoms->toJson(),
        ]);

        return redirect()->route('admin.diagnoses.index')->with('success', 'Diagnosis updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $decryptedId = decrypt($id);
            $diagnosis = Diagnosis::findOrFail($decryptedId);

            $diagnosis->delete();
            return redirect()->route('admin.diagnoses.index')->with('success', 'Diagnosis deleted successfully.');
        } catch (\Throwable $th) {
            return redirect()->route('admin.diagnoses.index')->with('error', 'Diagnosis not found');
        }
    }

    public function trashed()
    {
        if (request()->ajax()) {
            $query = Diagnosis::orderby('updated_at', 'desc')->onlyTrashed();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('symptoms', function ($item) {
                    $symptoms = json_decode($item->symptoms, true);

                    if (!is_array($symptoms)) {
                        return '-';
                    }
                    $codes = collect($symptoms)->pluck('code')->toArray();
                    $symptomNames = Symptom::whereIn('code', $codes)->pluck('description', 'code');

                    return collect($symptoms)->map(function ($s) use ($symptomNames) {
                        $code = $s['code'];
                        $name = $symptomNames[$code] ?? '-';
                        $confidence = isset($s['confidence']) ? $s['confidence'] : ($s['keyakinan'] ?? 0);
                        return "$code - $name (" . ($confidence * 100) . "%)";
                    })->implode('<br>');
                })
                ->addColumn('action', function ($item) {
                    $encryptedId = encrypt($item->id);
                    return '
                        <form class="block w-full" onsubmit="return confirm(\'Are you sure you want to restore?\');" action="' . route('admin.diagnoses.restore', $encryptedId) . '" method="POST">
                            ' . method_field('post') . csrf_field() . ' 
                            <button class="w-full px-2 py-1 mb-1 text-xs text-center text-white transition duration-500 bg-blue-700 border border-blue-700 rounded-md select-none ease hover:bg-blue-800 focus:outline-none focus:shadow-outline">
                                Restore
                            </button>
                        </form>
                        <form class="block w-full" onsubmit="return confirm(\'Are you sure you want to permanently delete?\');" action="' . route('admin.diagnoses.forceDelete', $encryptedId) . '" method="POST">
                            ' . method_field('delete') . csrf_field() . '
                            <button class="w-full px-2 py-1 text-xs text-white transition duration-500 bg-red-500 border border-red-500 rounded-md select-none ease hover:bg-red-600 focus:outline-none focus:shadow-outline">
                                Force Delete
                            </button>
                        </form>';
                })
                ->rawColumns(['action', 'symptoms'])
                ->make();
        }

        return view('admin.diagnoses.trashed');
    }

    public function restore(string $id)
    {
        try {
            $decryptedId = decrypt($id);
            $disease = Diagnosis::withTrashed()->findOrFail($decryptedId);
            $disease->restore();

            return redirect()->route('admin.diagnoses.trashed')->with('success', 'Diagnosis restored successfully.');
        } catch (DecryptException $e) {
            return redirect()->route('admin.diagnoses.trashed')->with('error', 'Diagnosis not found');
        }
    }

    public function forceDelete(string $id)
    {
        try {
            $decryptedId = decrypt($id);
            $disease = Diagnosis::withTrashed()->findOrFail($decryptedId);

            $disease->forceDelete();
            return redirect()->route('admin.diagnoses.trashed')->with('success', 'Diagnosis permanently deleted successfully');
        } catch (DecryptException $e) {
            return redirect()->route('admin.diagnoses.trashed')->with('error', 'Diagnosis not found');
        }
    }
}
