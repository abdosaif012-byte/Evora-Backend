<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

// class UserController extends Controller
// {
//    public function index()
//    {

//       $users = User::all();
      

//       return response()->json($users);
//    }

//    public function show($id)
//    {
//       $user = User::find($id);

//       if (!$user) {
//          return response()->json([
//             'message' => 'User not found'
//          ], 500);
//       }

//       return response()->json($user);
//    }

//    public function store(Request $request)
//    {
//       $validated = $request->validate([
//          "name" => "required|string|max:255",
//          "phone" => "required |string |max:20 ",
//          "email" => "required|email",
//          "password" => "required|string|min:8",
//          "dob"=>"required|date",
//          "address"=>"required|string|max:255",
//          "profile_image"=>"nullable|string|max:255",
//       ]);

//       $user = User::create($validated);

//       return response()->json([
//          "message" => "User created Successfully",
//          "user" => $user,
//       ], 201);
//    }

//    public function update(Request $request, $id)
//    {
//       $user = User::find($id);

//       if (!$user) {
//          return response()->json([
//             'message' => 'User not found'
//          ], 500);
//       }
//       $user->update([
//          "name" => $request->name,
//          "email" => $request->email,
//          "phone" => $request->phone,
//          "dob" => $request->dob,
//          "address" => $request->address,
//          "profile_image" => $request->profile_image,
//       ]);

//       return response()->json([
//          "message" => "User updated Successfully",
//          "user" => $user,
//       ], 200);
//    }

//    public function destroy($id)
//    {
//       $user = User::find($id);

//       if (!$user) {
//          return response()->json([
//             'message' => 'User not found'
//          ], 500);
//       }

//       $user->delete();

//       return response()->json([
//          'message' => 'User deleted successfully'
//       ]);
//    }
// }



// class UserController extends Controller
// {
//     public function index()
//     {
//         $user = auth()->user();

//         if ($user->role !== 'admin') {
//             return response()->json([
//                 'message' => 'Only admins can view users'
//             ], 403);
//         }

//         return response()->json(User::all());
//     }


//     public function show($id)
//     {
//         $user = auth()->user();

//         if ($user->role !== 'admin') {
//             return response()->json([
//                 'message' => 'Only admins can view a user'
//             ], 403);
//         }

//         return response()->json(User::findOrFail($id));
//     }


//     public function update(Request $request, $id)
//     {
//         $user = auth()->user();

//         if ($user->role !== 'admin') {
//             return response()->json([
//                 'message' => 'Only admins can edit users'
//             ], 403);
//         }

//         $targetUser = User::findOrFail($id);

//         $targetUser->update([
//             'name' => $request->name,
//             'phone' => $request->phone,
//             'email' => $request->email,
//         ]);

//         return response()->json([
//             'message' => 'User updated successfully',
//             'user' => $targetUser
//         ]);
//     }


//     public function destroy($id)
//     {
//         $user = auth()->user();

//         if ($user->role !== 'admin') {
//             return response()->json([
//                 'message' => 'Only admins can delete users'
//             ], 403);
//         }

//         $targetUser = User::findOrFail($id);

//         $targetUser->delete();

//         return response()->json([
//             'message' => 'User deleted successfully'
//         ]);
//     }
// }



class UserController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role !== 'admin') {
            return response()->json([
                'message' => 'Only admins can view users'
            ], 403);
        }

        return response()->json(User::all());
    }

    public function show($id)
    {
        $user = auth()->user();

        if ($user->role !== 'admin') {
            return response()->json([
                'message' => 'Only admins can view a user'
            ], 403);
        }

        return response()->json(User::findOrFail($id));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if ($user->role !== 'admin') {
            return response()->json([
                'message' => 'Only admins can create users'
            ], 403);
        }

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'phone'    => 'required|string|max:20',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'dob'      => 'required|date',
            'address'  => 'required|string|max:255',
            'role'     => 'required|string|in:admin,customer',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        $newUser = User::create($validated);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $newUser,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();

        if ($user->role !== 'admin') {
            return response()->json([
                'message' => 'Only admins can edit users'
            ], 403);
        }

        $targetUser = User::findOrFail($id);

        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'required|string|max:20',
            'email'   => 'required|email|unique:users,email,' . $id,
            'dob'     => 'required|date',
            'address' => 'required|string|max:255',
            'role'    => 'required|string|in:admin,customer',
        ]);

        $targetUser->update($validated);

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $targetUser
        ]);
    }

    public function destroy($id)
    {
        $user = auth()->user();

        if ($user->role !== 'admin') {
            return response()->json([
                'message' => 'Only admins can delete users'
            ], 403);
        }

        $targetUser = User::findOrFail($id);

        $targetUser->delete();

        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }
}