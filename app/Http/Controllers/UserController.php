<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
        ]);

        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(5);

        return response()->json([
            'status' => true,
            'message' => 'User List',
            'data' => $users
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['status' => true, 'message' => 'User deleted']);
    }

    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        return response()->json(['status' => true, 'message' => 'User restored']);
    }

    public function toggleStatus(User $user)
    {
        $user->status = !$user->status;
        $user->save();
        return response()->json(['status' => true, 'message' => 'Status updated']);
    }

    public function export()
    {
        $users = User::all();
        $csv = "id,name,email,status\n";
        foreach ($users as $user) {
            $csv .= "{$user->id},{$user->name},{$user->email}," . ($user->status ? 'Active' : 'Inactive') . "\n";
        }
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="users.csv"');
    }
}