<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
public function index()
{
$users = User::paginate(10);
return view('pages.user.index', compact('users'));
}

public function store(Request $request)
{
$request->validate([
'name' => 'required|string|max:255',
'email' => 'required|string|email|unique:users',
'password' => 'required|string|min:6',
]);

User::create([
'name' => $request->name,
'email' => $request->email,
'password' => bcrypt($request->password),
]);

return redirect()->back()->with('success', 'User berhasil ditambahkan!');
}
}