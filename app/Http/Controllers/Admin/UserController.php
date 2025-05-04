<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        if (request()->ajax()) {
            $query = User::query();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($user) {

                    if ($user->role == 'superadmin') {
                        return '';
                    }

                    $encryptedId = encrypt($user->id);
                    return '
                        <a class="block w-full px-2 py-1 mb-1 text-xs text-center text-white transition duration-500 bg-blue-700 border border-blue-700 rounded-md select-none ease hover:bg-blue-800 focus:outline-none focus:shadow-outline" 
                            href="' . route('admin.users.edit', $encryptedId) . '">
                            Edit
                        </a>
                        <form class="block w-full" onsubmit="return confirm(\'Are you sure want to delete?\');" -block" action="' . route('admin.users.destroy', $encryptedId) . '" method="POST">
                        <button class="w-full px-2 py-1 text-xs text-white transition duration-500 bg-red-500 border border-red-500 rounded-md select-none ease hover:bg-red-600 focus:outline-none focus:shadow-outline" >
                            Delete
                        </button>
                            ' . method_field('delete') . csrf_field() . '
                        </form>';
                })
                ->rawColumns(['action'])
                ->make();
        }

        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = ['admin', 'user'];
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {

        $data = $request->only(['name', 'email', 'password', 'role']);
        $data['password'] = Hash::make($data['password']);

        User::create($data);
        return redirect()->route('admin.users.index')->with('success', 'User created successfully');
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
            $user = User::findOrFail($decryptedId);
            $roles = ['admin', 'user'];

            return view('admin.users.edit', compact('user', 'roles'));
        } catch (DecryptException $e) {
            return redirect()->route('admin.users.index')->with('error', 'User not found');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->only(['name', 'email', 'role']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $decryptedId = decrypt($id);
            $user = User::findOrFail($decryptedId);

            $user->delete();
            return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
        } catch (DecryptException $e) {
            return redirect()->route('admin.users.index')->with('error', 'User not found');
        }
    }

    public function trashed()
    {
        if (request()->ajax()) {
            $query = User::onlyTrashed();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($user) {
                    $encryptedId = encrypt($user->id);
                    return '
                        <form class="block w-full" onsubmit="return confirm(\'Are you sure you want to restore this user?\');" action="' . route('admin.users.restore', $encryptedId) . '" method="POST">
                            ' . method_field('post') . csrf_field() . ' 
                            <button class="w-full px-2 py-1 mb-1 text-xs text-center text-white transition duration-500 bg-blue-700 border border-blue-700 rounded-md select-none ease hover:bg-blue-800 focus:outline-none focus:shadow-outline">
                                Restore
                            </button>
                        </form>
                        <form class="block w-full" onsubmit="return confirm(\'Are you sure you want to permanently delete this user?\');" action="' . route('admin.users.forceDelete', $encryptedId) . '" method="POST">
                            ' . method_field('delete') . csrf_field() . '
                            <button class="w-full px-2 py-1 text-xs text-white transition duration-500 bg-red-500 border border-red-500 rounded-md select-none ease hover:bg-red-600 focus:outline-none focus:shadow-outline">
                                Force Delete
                            </button>
                        </form>';
                })
                ->rawColumns(['action'])
                ->make();
        }

        return view('admin.users.trashed');
    }

    public function restore(string $id)
    {
        try {
            $decryptedId = decrypt($id);
            $user = User::onlyTrashed()->findOrFail($decryptedId);
            $user->restore();

            return redirect()->route('admin.users.trashed')->with('success', 'Item restored successfully');
        } catch (DecryptException $e) {
            return redirect()->route('admin.users.trashed')->with('error', 'Invalid user ID');
        }
    }

    public function forceDelete(string $id)
    {
        try {
            $decryptedId = decrypt($id);
            $user = User::onlyTrashed()->findOrFail($decryptedId);

            $user->forceDelete();
            return redirect()->route('admin.users.trashed')->with('success', 'User permanently deleted successfully');
        } catch (DecryptException $e) {
            return redirect()->route('admin.users.trashed')->with('error', 'Invalid User ID');
        }
    }
}
