<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'view_course',
            'create_course',
            'edit_course',
            'delete_course',
        ];
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
        $teacherRole = Role::create(['name' => 'teacher']);
        $teacherRole->givePermissionTo([
            'view_course',
            'create_course',
            'edit_course',
            'delete_course',
        ]);
        $studentRole = Role::create(['name' => 'student']);
        $studentRole->givePermissionTo([
            'view_course',
        ]);

//        create data for super admin
        $user = User::create([
            'name' => 'Fany Indrawan',
            'email' => 'fany@teacher.com',
            'password' => bcrypt('12qwaszx'),
        ]);

        $user->assignRole('teacher');

    }
}
