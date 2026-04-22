<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // List Users with search
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('search')) {
            $query->where('name', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%");
        }

        return response()->json([
            'status' => true,
            'message' => 'User List',
            'data' => $query->get()
        ]);
    }

    // Soft Delete User
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['status' => true, 'message' => 'User deleted']);
    }

    // Restore Soft Deleted User
    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        return response()->json(['status' => true, 'message' => 'User restored']);
    }

    // Toggle Status
    public function toggleStatus(User $user)
    {
        $user->status = !$user->status;
        $user->save();
        return response()->json(['status' => true, 'message' => 'Status updated']);
    }

    // Export CSV
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