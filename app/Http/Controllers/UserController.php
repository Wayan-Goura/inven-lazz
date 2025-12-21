<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::whereIn('role', ['admin', 'super_admin'])->paginate(10);
        return view('pages.kel_role.index', compact('users'));
    }

    public function create()
    {
        return view('pages.kel_role.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'role' => 'required|in:admin,super_admin',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('kel_role.index')
            ->with('success', 'Admin berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        return view('pages.kel_role.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,super_admin',
        ]);

        $user->update($request->only('name', 'email', 'role'));

        return redirect()->route('kel_role.index')
            ->with('success', 'Admin berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        if ($user->role === 'super_admin') {
            return back()->with('error', 'Super Admin tidak bisa dihapus');
        }

        $user->delete();

        return redirect()->route('kel_role.index')
            ->with('success', 'Admin berhasil dihapus');
    }
}
