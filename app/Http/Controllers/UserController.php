<?php
namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Spatie\Permission\Models\Role as SpatieRole;

class UserController extends Controller
{
    public function index()
    {
        $authUser     = Auth::user();
        $authUserRole = $authUser->roles->first()?->name;

        $userQuery = User::with('roles')->orderBy('created_at', 'asc');

        if (! in_array($authUserRole, ['super-admin', 'admin', 'editor', 'user'])) {
            abort(403, 'Unauthorized Access Prevented');
        }

        if ($authUserRole === 'admin') {
            $userQuery->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'super-admin');
            });
        } elseif ($authUserRole === 'editor') {
            $userQuery->whereHas('roles', function ($q) {
                $q->whereIn('name', ['editor', 'user']);
            });
        } elseif ($authUserRole === 'user') {
            $userQuery->whereHas('roles', function ($q) {
                $q->whereIn('name', ['user']);
            });
        }

        $users = $userQuery->paginate(10);
        $roles = Role::all();

        return Inertia::render('users/index', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    public function create()
    {
        //
    }

    public function store(UserRequest $request)
    {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($user) {
            $roles = SpatieRole::whereIn('id', $request->roles)->pluck('name');
            $user->syncRoles($roles);

            return redirect()->route('users.index')->with('success', 'User created with roles');
        }

        return redirect()->back()->with('error', 'Unable to create User. Please try again!');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(User $user)
    {
        return Inertia::render('users/index', [
            'user' => $user->load('roles'),
            'roles' => Role::all(),
            'editing' => true
        ]);
    }

    public function update(UserRequest $request, User $user)
    {
        if ($user) {
            $user->name  = $request->name;
            $user->email = $request->email;
            $user->save();

            $roles = SpatieRole::whereIn('id', $request->roles)->pluck('name');
            $user->syncRoles($roles);

            return redirect()->route('users.index')->with('success', 'User updated with roles');
        }

        return redirect()->back()->with('error', 'Unable to update User. Please try again!');
    }

    public function destroy(User $user)
    {
        if ($user) {
            $user->delete();
            return redirect()->route('users.index')->with('success', 'User deleted with roles');
        }
        return redirect()->back()->with('error', 'Unable to delete User. Please try again!');
    }
}
