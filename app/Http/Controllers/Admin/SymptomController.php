<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSymptomRequest;
use App\Http\Requests\UpdateSymptomRequest;
use App\Models\Disease;
use App\Models\Symptom;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SymptomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $query = Symptom::with('disease');

            if ($request->filled('disease_id') && $request->input('disease_id') !== 'all') {
                $query->where('disease_id', $request->input('disease_id'));
            }

            $query->orderByRaw('CAST(SUBSTRING(code, 2) AS UNSIGNED) ASC');

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('code', function ($item) {
                    return $item->code;
                })
                ->addColumn('disease.name', function ($item) {
                    return $item->disease ? $item->disease->name : '<span class="text-red-500">Deleted</span>';
                })
                ->addColumn('action', function ($item) {
                    $encryptedId = encrypt($item->id);
                    return '
                        <a class="block w-full px-2 py-1 mb-1 text-xs text-center text-white transition duration-500 bg-blue-700 border border-blue-700 rounded-md select-none ease hover:bg-blue-800 focus:outline-none focus:shadow-outline" 
                            href="' . route('admin.symptoms.edit', $encryptedId) . '">
                            Edit
                        </a>
                        <form class="block w-full" onsubmit="return confirm(\'Are you sure want to delete?\');" -block" action="' . route('admin.symptoms.destroy', $encryptedId) . '" method="POST">
                        <button class="w-full px-2 py-1 text-xs text-white transition duration-500 bg-red-500 border border-red-500 rounded-md select-none ease hover:bg-red-600 focus:outline-none focus:shadow-outline" >
                            Delete
                        </button>
                            ' . method_field('delete') . csrf_field() . '
                        </form>';
                })
                ->rawColumns(['action', 'disease.name'])
                ->make();
        }

        $diseases = Disease::orderBy('name')->get();
        return view('admin.symptoms.index', compact('diseases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $diseases = Disease::all();
        return view('admin.symptoms.create', compact('diseases'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSymptomRequest $request)
    {
        $data = $request->all();
        $data['weight'] = $data['mb'] - $data['md'];

        Symptom::create($data);
        return redirect()->route('admin.symptoms.index')->with('success', 'Symptom created successfully');
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
            $symptom = Symptom::findOrFail($decryptedId);
            $diseases = Disease::all();

            return view('admin.symptoms.edit', compact('symptom', 'diseases'));
        } catch (DecryptException $e) {
            return redirect()->route('admin.symptoms.index')->with('error', 'Symptom not found');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSymptomRequest $request, Symptom $symptom)
    {
        $data = $request->all();
        $data['weight'] = $data['mb'] - $data['md'];

        $symptom->update($data);
        return redirect()->route('admin.symptoms.index')->with('success', 'Symptom updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $decryptedId = decrypt($id);
            $symptom = Symptom::findOrFail($decryptedId);

            $symptom->delete();
            return redirect()->route('admin.symptoms.index')->with('success', 'Symptom deleted successfully.');
        } catch (DecryptException $e) {
            return redirect()->route('admin.symptoms.index')->with('error', 'Symptom not found');
        }
    }

    public function trashed()
    {
        if (request()->ajax()) {
            $query = Symptom::onlyTrashed();
            $query->orderByRaw('CAST(SUBSTRING(code, 2) AS UNSIGNED) ASC');

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('code', function ($item) {
                    return $item->code;
                })
                ->addColumn('disease.name', function ($item) {
                    return $item->disease ? $item->disease->name : '<span class="text-red-500">Deleted</span>';
                })
                ->addColumn('action', function ($item) {
                    $encryptedId = encrypt($item->id);
                    return '
                        <form class="block w-full" onsubmit="return confirm(\'Are you sure you want to restore?\');" action="' . route('admin.symptoms.restore', $encryptedId) . '" method="POST">
                            ' . method_field('post') . csrf_field() . ' 
                            <button class="w-full px-2 py-1 mb-1 text-xs text-center text-white transition duration-500 bg-blue-700 border border-blue-700 rounded-md select-none ease hover:bg-blue-800 focus:outline-none focus:shadow-outline">
                                Restore
                            </button>
                        </form>
                        <form class="block w-full" onsubmit="return confirm(\'Are you sure you want to permanently delete?\');" action="' . route('admin.symptoms.forceDelete', $encryptedId) . '" method="POST">
                            ' . method_field('delete') . csrf_field() . '
                            <button class="w-full px-2 py-1 text-xs text-white transition duration-500 bg-red-500 border border-red-500 rounded-md select-none ease hover:bg-red-600 focus:outline-none focus:shadow-outline">
                                Force Delete
                            </button>
                        </form>';
                })
                ->rawColumns(['action', 'disease.name'])
                ->make();
        }

        return view('admin.symptoms.trashed');
    }

    public function restore(string $id)
    {
        try {
            $decryptedId = decrypt($id);
            $symptom = Symptom::withTrashed()->findOrFail($decryptedId);
            $symptom->restore();
            return redirect()->route('admin.symptoms.trashed')->with('success', 'Symptom restored successfully');
        } catch (DecryptException $e) {
            return redirect()->route('admin.symptoms.trashed')->with('error', 'Symptom not found');
        }
    }

    public function forceDelete(string $id)
    {
        try {
            $decryptedId = decrypt($id);
            $symptom = Symptom::withTrashed()->findOrFail($decryptedId);
            $symptom->forceDelete();
            return redirect()->route('admin.symptoms.trashed')->with('success', 'Symptom permanently deleted successfully');
        } catch (DecryptException $e) {
            return redirect()->route('admin.symptoms.trashed')->with('error', 'Symptom not found');
        }
    }
}
