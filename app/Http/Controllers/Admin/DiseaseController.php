<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDiseaseRequest;
use App\Http\Requests\UpdateDiseaseRequest;
use App\Models\Disease;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DiseaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $query = Disease::query();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($item) {
                    $encryptedId = encrypt($item->id);
                    return '
                        <a class="block w-full px-2 py-1 mb-1 text-xs text-center text-white transition duration-500 bg-blue-700 border border-blue-700 rounded-md select-none ease hover:bg-blue-800 focus:outline-none focus:shadow-outline" 
                            href="' . route('admin.diseases.edit', $encryptedId) . '">
                            Edit
                        </a>
                        <form class="block w-full" onsubmit="return confirm(\'Are you sure want to delete?\');" -block" action="' . route('admin.diseases.destroy', $encryptedId) . '" method="POST">
                        <button class="w-full px-2 py-1 text-xs text-white transition duration-500 bg-red-500 border border-red-500 rounded-md select-none ease hover:bg-red-600 focus:outline-none focus:shadow-outline" >
                            Delete
                        </button>
                            ' . method_field('delete') . csrf_field() . '
                        </form>';
                })
                ->rawColumns(['action'])
                ->make();
        }

        return view('admin.diseases.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.diseases.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDiseaseRequest $request)
    {
        $data = $request->all();
        Disease::create($data);
        return redirect()->route('admin.diseases.index')->with('success', 'Disease created successfully');
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
            $disease = Disease::findOrFail($decryptedId);
            return view('admin.diseases.edit', compact('disease'));
        } catch (DecryptException $th) {
            return redirect()->route('admin.diseases.index')->with('error', 'Disease not found');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDiseaseRequest $request, Disease $disease)
    {
        $data = $request->all();
        $disease->update($data);

        return redirect()->route('admin.diseases.index')->with('success', 'Disease updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $decryptedId = decrypt($id);
            $disease = Disease::findOrFail($decryptedId);

            $disease->delete();
            return redirect()->route('admin.diseases.index')->with('success', 'Disease deleted successfully.');
        } catch (DecryptException $e) {
            return redirect()->route('admin.diseases.index')->with('error', 'Disease not found');
        }
    }

    public function trashed()
    {
        if (request()->ajax()) {
            $query = Disease::onlyTrashed();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($item) {
                    $encryptedId = encrypt($item->id);
                    return '
                        <form class="block w-full" onsubmit="return confirm(\'Are you sure you want to restore?\');" action="' . route('admin.diseases.restore', $encryptedId) . '" method="POST">
                            ' . method_field('post') . csrf_field() . ' 
                            <button class="w-full px-2 py-1 mb-1 text-xs text-center text-white transition duration-500 bg-blue-700 border border-blue-700 rounded-md select-none ease hover:bg-blue-800 focus:outline-none focus:shadow-outline">
                                Restore
                            </button>
                        </form>
                        <form class="block w-full" onsubmit="return confirm(\'Are you sure you want to permanently delete?\');" action="' . route('admin.diseases.forceDelete', $encryptedId) . '" method="POST">
                            ' . method_field('delete') . csrf_field() . '
                            <button class="w-full px-2 py-1 text-xs text-white transition duration-500 bg-red-500 border border-red-500 rounded-md select-none ease hover:bg-red-600 focus:outline-none focus:shadow-outline">
                                Force Delete
                            </button>
                        </form>';
                })
                ->rawColumns(['action'])
                ->make();
        }

        return view('admin.diseases.trashed');
    }

    public function restore(string $id)
    {
        try {
            $decryptedId = decrypt($id);
            $disease = Disease::withTrashed()->findOrFail($decryptedId);
            $disease->restore();

            return redirect()->route('admin.diseases.trashed')->with('success', 'Disease restored successfully.');
        } catch (DecryptException $e) {
            return redirect()->route('admin.diseases.trashed')->with('error', 'Disease not found');
        }
    }

    public function forceDelete(string $id)
    {
        try {
            $decryptedId = decrypt($id);
            $disease = Disease::withTrashed()->findOrFail($decryptedId);

            $disease->forceDelete();
            return redirect()->route('admin.diseases.trashed')->with('success', 'Disease permanently deleted successfully');
        } catch (DecryptException $e) {
            return redirect()->route('admin.diseases.trashed')->with('error', 'Disease not found');
        }
    }
}
