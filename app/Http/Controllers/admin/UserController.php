<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\log;


class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query();

        if (!empty($request->get('keyword'))) {
            $users = $users->where('name', 'like', '%' . $request->get('keyword') . '%');
            $users = $users->orwhere('email', 'like', '%' . $request->get('keyword') . '%');
        }

        $users = $users->orderBy('id', 'asc')->paginate(10);
        return view('admin.users.list', [
            'users' => $users
        ]);
    }


    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validator = validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'phone' => 'required'
        ]);
        if ($validator->passes()) {
            $user = new User();
            $user->name = $request->name;
            $user->status = $request->status;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->phone = $request->phone;
            $user->save();

            $message = 'User Added Successfully';
            $request->session()->flash('success', $message);
            return response()->json([
                'status' => true,
                'message' => $message
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function edit(Request $request, $id)
    {
        $user = user::find($id);
        if ($user == null) {
            $message = 'User not Found';
            $request->session()->flash('error', $message);
        }
        return view('admin.users.edit', [
            'user' => $user
        ]);
    }
    public function update(Request $request, $id)
    {
        $user = user::find($id);

        if ($user == null) {
            $message = 'User not Found';
            $request->session()->flash('error', $message);

            return response()->json([
                'status' => true,
                'message' => $message
            ]);
        }

        $validator = validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id . ',id',
            'phone' => 'required'
        ]);

        if ($validator->passes()) {

            $user->name = $request->name;
            $user->email = $request->email;
            $user->status = $request->status;
            if ($request->password != '') {
                $user->password = Hash::make($request->password);
            }
            $user->phone = $request->phone;
            $user->save();

            $message = 'User Updated Successfully';
            $request->session()->flash('success', $message);
            return response()->json([
                'status' => true,
                'message' => $message
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function destroy(Request $request, $id)
    {
        $user = User::find($id);
    
        if ($user == null) {
            $message = 'User not Found';
            $request->session()->flash('error', $message);
            return response()->json(['status' => false, 'message' => $message], 404);
        }
    
        if ($user->delete()) {
            $message = 'User Deleted successfully';
            $request->session()->flash('success', $message);
            return response()->json(['status' => true, 'message' => $message], 200);
        } else {
            Log::error("Error deleting user: " . $user->id);
            return response()->json(['status' => false, 'message' => 'An error occurred while deleting the user'], 500);
        }
    }
}
