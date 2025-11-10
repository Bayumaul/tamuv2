<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $masterLoket = [
            1 => 'Loket 1 - Kekayaan Intelektual',
            2 => 'Loket 2 - Administrasi Hukum Umum',
        ];
        $users = User::where('role', 'petugas')->get();
        return view('dashboard.users.index', compact('masterLoket', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $masterLoket = [
            1 => 'Loket 1 - Kekayaan Intelektual',
            2 => 'Loket 2 - Administrasi Hukum Umum',
        ];
        return view('dashboard.users.create', compact('masterLoket'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'id_loket' => ['required', 'integer', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'id_loket' => $request->id_loket,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'Petugas berhasil ditambahkan !');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return 'abse2';
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return 'abse4';
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return 'abse4';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return 'abse6';
    }
    public function getUsersData()
    {
        $users = User::all();

        $masterLoket = [1 => 'Loket 1', 2 => 'Loket 2', 3 => 'Loket 3', 4 => 'Loket 4'];

        $data = $users->map(function ($user) use ($masterLoket) {
            return [
                'user' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'loket' => $masterLoket[$user->id_loket] ?? 'Belum Diatur',
                'status' => 'Active',
                'actions' => '<button data-id="' . $user->id . '" class="btn btn-sm btn-icon btn-label-primary edit-user-btn"><i class="ti ti-edit"></i></button>
                              <button data-id="' . $user->id . '" class="btn btn-sm btn-icon btn-label-danger delete-user-btn"><i class="ti ti-trash"></i></button>',
            ];
        });

        return response()->json(['data' => $data]);
    }
}
