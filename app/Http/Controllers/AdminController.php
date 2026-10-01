<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Fellows;
use App\Models\Navigation;
use Illuminate\Http\Request;
use App\Models\NavCategories;
use Spatie\Permission\Models\Role;
use App\Forms\CreateNavigationForm;
use App\Tables\AdminNavigationTable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class AdminController extends Controller
{
    private function canViewAsFellow(): bool
    {
        $user = Auth::user();
        return $user && $user->hasAnyRole(['admin', 'super admin']);
    }

    private function ensureViewAsFellowAccess()
    {
        if (!$this->canViewAsFellow()) {
            abort(403);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $perms = $user->getRoleNames();
        // dd($user->roles);
        // dd([$user, $perms]);
        if($user->hasRole('super admin')) {
            return view('admin.index');
        }
        else {
            return redirect()->route('home');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.newuser');
    }

    public function resetPassword(User $user) {
        return view('admin.resetpassword', [
            'user' => $user,
        ]);
    }

    public function updatePassword(Request $request, User $user) {
        $password = $request->password;
        $user->update([
            'password' => Hash::make($password),
        ]);
        return redirect()->back();
    }

    public function navigation()
    {
        $keys = array_keys(\Illuminate\Support\Facades\Route::getRoutes()->getRoutesByName());
        $routes = array_combine($keys, $keys);
        // dd(Navigation::all());
        return view('admin.navigation', [
            'nav' => Navigation::all(),
            'routes' => $routes,
        ]);
    }

    public function viewAsFellow()
    {
        $this->ensureViewAsFellowAccess();

        $fellows = Fellows::optionsAll();
        $currentFellowId = session('view_as_fellow_id');
        $currentFellowName = null;

        if ($currentFellowId && isset($fellows[$currentFellowId])) {
            $currentFellowName = $fellows[$currentFellowId];
        } elseif ($currentFellowId) {
            session()->forget('view_as_fellow_id');
            $currentFellowId = null;
        }

        return view('admin.view-as-fellow', [
            'fellows' => $fellows,
            'currentFellowId' => $currentFellowId,
            'currentFellowName' => $currentFellowName,
        ]);
    }

    public function setViewAsFellow(Request $request)
    {
        $this->ensureViewAsFellowAccess();

        $input = $request->validate([
            'fellow_id' => 'required|exists:fellows,id',
        ]);

        session(['view_as_fellow_id' => (int) $input['fellow_id']]);

        return redirect()->back();
    }

    public function clearViewAsFellow()
    {
        $this->ensureViewAsFellowAccess();

        session()->forget('view_as_fellow_id');

        return redirect()->route('admin.view_as_fellow');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);
        $role = Role::find($request->roles);
        if(!is_null($role)) {
            $user->syncRoles([$role->name]);
        }

        return redirect()->back();
    }

    public function navCreate() {
        return view('admin.navcreate', [
            'form' => CreateNavigationForm::class,
        ]);
    }

    public function navStore(Request $request)
    {
        $input = $request->validate(CreateNavigationForm::rules());
        Navigation::create($input);

        return redirect()->back();
    }

    public function navUpdate(Request $request, string $id)
    {
        $nav = Navigation::find($id);
        if($request->has('name')) {
            $nav->name = $request->name;
        }
        if($request->has('route')) {
            $nav->route = $request->route;
        }
        if($request->has('category')) {
            $nav->category = $request->category;
        }
        if($request->has('order')) {
            $nav->order = $request->order;
        }
        if($request->has('permission')) {
            $nav->permission = $request->permission;
        }
        $nav->save();

        return redirect()->back();
    }

    public function navDelete(string $id) {
        Navigation::destroy($id);
        return redirect()->back();
    }

    public function catUpdate(Request $request, string $id)
    {
        $cat = NavCategories::find($id);
        if($request->has('category')) {
            $cat->category = $request->category;
        }
        if($request->has('order')) {
            $cat->order = $request->order;
        }
        if($request->has('colour')) {
            $cat->colour = $request->colour;
        }
        $cat->save();

        return redirect()->back();
    }

    public function perms()
    {
        return view('admin.permissions');
    }

    public function roleUpdate(Request $request, string $id)
    {
        $role = Role::find($id);

        if($request->has('name')) {
            $role->name = $request->name;
        }
        if($request->has('permissions')) {
            $newPerms = [];
            foreach($request->permissions as $perm) {
                array_push($newPerms, Permission::find($perm));
            }
            $role->syncPermissions($newPerms);
        }
        $role->save();

        return redirect()->back();
    }

    public function newRole()
    {
        return view('admin.newrole');
    }

    public function createRole(Request $request)
    {
        $role = new Role([
            'name' => $request->name,
        ]);
        $role->save();

        if($request->filled('permissions')) {
            $newPerms = [];
            foreach($request->permissions as $perm) {
                array_push($newPerms, Permission::find($perm));
            }
            $role->syncPermissions($newPerms);
        }
        
        return redirect()->back();
    }

    public function deleteRole(string $id)
    {
        Role::destroy($id);
        return redirect()->back();
    }

    public function permUpdate(Request $request, string $id)
    {
        $perm = Permission::find($id);
        if($request->has('name')) {
            $perm->name = $request->name;
        }
        $perm->save();

        return redirect()->back();
    }

    public function newPerm()
    {
        return view('admin.newperm');
    }

    public function createPerm(Request $request)
    {
        $perm = new Permission([
            'name' => $request->name,
        ]);
        $perm->save();

        return redirect()->back();
    }

    public function deletePerm(string $id)
    {
        Permission::destroy($id);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User::destroy($id);
        return redirect()->back();
    }
}
