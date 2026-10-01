<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'view']);
        Permission::create(['name' => 'edit']);
        Permission::create(['name' => 'delete']);
        Permission::create(['name' => 'grad']);
        Permission::create(['name' => 'admin']);

        $role1 = Role::create(['name' => 'viewer']);
        $role1->givePermissionTo('view');

        $role2 = Role::create(['name' => 'editor']);
        $role2->givePermissionTo('view');
        $role2->givePermissionTo('edit');

        $role3 = Role::create(['name' => 'admin']);
        $role3->givePermissionTo('view');
        $role3->givePermissionTo('edit');
        $role3->givePermissionTo('grad');
        $role3->givePermissionTo('admin');

        $role4 = Role::create(['name' => 'csgrad']);
        $role4->givePermissionTo('grad');

        $role5 = Role::create(['name' => 'super admin']);
        $role5->givePermissionTo('view');
        $role5->givePermissionTo('edit');
        $role5->givePermissionTo('delete');
        $role5->givePermissionTo('grad');
        $role5->givePermissionTo('admin');

        $user = User::find(1);
        $user->assignRole('super admin');

        for($i = 2; $i <= 6; $i++) {
            $user = User::find($i);
            $user->assignRole('admin');
        }
    }
}
