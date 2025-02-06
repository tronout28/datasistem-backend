<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return response()->json([
            'success' => 'success',
            'data' => $users,
        ]);
    }

    public function profile(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => 'success',
            'data' => $user,
        ]);
    }

    public function MakeUseraccount(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|unique:users,phone_number',
            'password' => 'required|string|confirmed|min:6',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5000',
            'role' => ['required',Rule::in(['admin','marketing','produksi'])],
        ]);

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        $user->save();

        if ($request->hasFile('profile_picture')) {
            $profile_picture = $request->file('profile_picture');
            $imageName = time() . '.' . $profile_picture->extension();
            $profile_picture->move(public_path('profile_picture'), $imageName);

            if ($user->profile_picture && file_exists(public_path('profile_picture/' . $user->profile_picture))) {
                unlink(public_path('profile_picture/' . $user->profile_picture));
            }

            $user->profile_picture = $imageName;
            $user->profile_picture = url('profile_picture/' . $user->profile_picture);

            $user->save();
        }

        return response()->json([
            'success' => 'success',
            'data' => $user,
        ]);
    }

    public function UpdateProfile(Request $request)
    {
        $request->validate([
            'email' => 'nullable|email|unique:users,email,' . $request->user()->id,
            'phone_number' => 'nullable|unique:users,phone_number,' . $request->user()->id,
            'password' => 'nullable|string|confirmed|min:6',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5000',
        ]);

        $user = $request->user();
        $user->email = $request->email ?? $user->email;
        $user->phone_number = $request->phone_number ?? $user->phone_number;

        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        if ($request->hasFile('profile_picture')) {
            $profile_picture = $request->file('profile_picture');
            $imageName = time() . '.' . $profile_picture->extension();
            $profile_picture->move(public_path('profile_picture'), $imageName);

            if ($user->profile_picture && file_exists(public_path('profile_picture/' . $user->profile_picture))) {
                unlink(public_path('profile_picture/' . $user->profile_picture));
            }

            $user->profile_picture = $imageName;
            $user->profile_picture = url('profile_picture/' . $user->profile_picture);
        }

        $user->save();

        return response()->json([
            'success' => 'success',
            'data' => $user,
        ]);
    }

    public function TotalUser()
    {
        $total = User::count();

        return response()->json([
            'success' => 'success',
            'data' => $total,
        ]);
    }
}
