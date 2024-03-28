<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Step 1: Create permissions
        $editTask = Permission::create(['name' => 'edit task']);
        $deleteTask = Permission::create(['name' => 'delete task']);
        $createTask = Permission::create(['name' => 'create task']);
        $viewTask = Permission::create(['name' => 'view task']);

        $editProject = Permission::create(['name' => 'edit project']);
        $deleteProject = Permission::create(['name' => 'delete project']);
        $createProject = Permission::create(['name' => 'create project']);
        $viewProject = Permission::create(['name' => 'view project']);

        $deleteUser = Permission::create(['name' => 'delete user']);

// Step 2: Create roles
        $admin = Role::create(['name' => 'super_admin']);
        $manager = Role::create(['name' => 'manager']);
        $employee = Role::create(['name' => 'employee']);

// Step 3: Assign permissions to roles
// Admin has all permissions
        $admin->givePermissionTo($editTask);
        $admin->givePermissionTo($deleteTask);
        $admin->givePermissionTo($createTask);
        $admin->givePermissionTo($viewTask);
        $admin->givePermissionTo($editProject);
        $admin->givePermissionTo($deleteProject);
        $admin->givePermissionTo($createProject);
        $admin->givePermissionTo($viewProject);
        $admin->givePermissionTo($deleteUser);

// Manager can edit, create, view tasks and projects but cannot delete
        $manager->givePermissionTo($editTask);
        $manager->givePermissionTo($deleteTask);
        $manager->givePermissionTo($createTask);
        $manager->givePermissionTo($viewTask);
        $manager->givePermissionTo($editProject);
        $manager->givePermissionTo($deleteProject);
        $manager->givePermissionTo($createProject);
        $manager->givePermissionTo($viewProject);

// Employee can only view tasks and projects
        $employee->givePermissionTo($viewTask);
        $employee->givePermissionTo($viewProject);
        $employee->givePermissionTo($editTask);
        $employee->givePermissionTo($createTask);


    }
}
