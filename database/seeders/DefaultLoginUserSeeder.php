<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class DefaultLoginUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Super Admin Seeder ##
        $superAdminRole = Role::updateOrCreate(['name'=> 'Super Admin']);
        $permissions = Permission::pluck('id','id')->all();
        $superAdminRole->syncPermissions($permissions);

        $user = User::updateOrCreate([
            'email' => 'superadmin@gmail.com'
        ],[
            'first_name' => 'Super',
            'middle_name' => '',
            'last_name' => 'Admin',
            'gender' => 'male',
            'mobile' => '9999999991',
            'email' => 'superadmin@gmail.com',
            'username' => 'superadmin@gmail.com',
            'password' => Hash::make('12345678'),
        ]);
        $user->assignRole([$superAdminRole->id]);



        // Admin Seeder ##
        $adminRole = Role::updateOrCreate(['name'=> 'Admin']);
        $permissions = Permission::pluck('id','id')->all();
        $adminRole->syncPermissions($permissions);

        $user = User::updateOrCreate([
            'email' => 'admin@gmail.com'
        ],[
            'first_name' => 'Admin',
            'middle_name' => '',
            'last_name' => '',
            'gender' => 'male',
            'mobile' => '9999999992',
            'email' => 'admin@gmail.com',
            'username' => 'admin@gmail.com',
            'password' => Hash::make('12345678')
        ]);
        $user->assignRole([$adminRole->id]);



        // Department Seeder ##
        $departmentRole = Role::updateOrCreate(['name'=> 'Department']);
        $permissions = Permission::where('group', 'department_audit')->pluck('id','id');
        $departmentRole->syncPermissions($permissions);

        $user = User::updateOrCreate([
            'email' => 'department@gmail.com'
        ],[
            'first_name' => 'Department',
            'middle_name' => '',
            'last_name' => '',
            'gender' => 'male',
            'mobile' => '9999999993',
            'email' => 'department@gmail.com',
            'department_id' => '3',
            'username' => 'department@gmail.com',
            'password' => Hash::make('12345678')
        ]);
        $user->assignRole([$departmentRole->id]);



        // Auditor Seeder ##
        $auditorRole = Role::updateOrCreate(['name'=> 'Auditor']);
        $permissions = Permission::where('group', 'auditor_audit')->pluck('id','id');
        $auditorRole->syncPermissions($permissions);

        $user = User::updateOrCreate([
            'email' => 'auditor@gmail.com'
        ],[
            'first_name' => 'Auditor',
            'middle_name' => '',
            'last_name' => '',
            'gender' => 'male',
            'mobile' => '9999999994',
            'auditor_no' => '123456',
            'username' => 'auditor@gmail.com',
            'email' => 'auditor@gmail.com',
            'password' => Hash::make('12345678')
        ]);
        $user->assignRole([$auditorRole->id]);



        // MCA Seeder ##
        $mcaRole = Role::updateOrCreate(['name'=> 'MCA']);
        $permissions = Permission::where('group', 'mca_audit')->pluck('id','id');
        $mcaRole->syncPermissions($permissions);

        $user = User::updateOrCreate([
            'email' => 'mca@gmail.com'
        ],[
            'first_name' => 'MCA',
            'middle_name' => '',
            'last_name' => '',
            'gender' => 'male',
            'mobile' => '9999999995',
            'department_id' => '1',
            'username' => 'mca@gmail.com',
            'email' => 'mca@gmail.com',
            'password' => Hash::make('12345678')
        ]);
        $user->assignRole([$mcaRole->id]);



        // DY Seeder ##
        $dyRole = Role::updateOrCreate(['name'=> 'DY MCA']);
        $permissions = Permission::where('group', 'mca_audit')->whereNot('id', 28)->pluck('id','id');
        $dyRole->syncPermissions($permissions);

        $user = User::updateOrCreate([
            'email' => 'dymca@gmail.com'
        ],[
            'first_name' => 'DY',
            'middle_name' => 'MCA',
            'last_name' => '',
            'gender' => 'male',
            'mobile' => '9999999996',
            'department_id' => '1',
            'username' => 'dymca@gmail.com',
            'email' => 'dymca@gmail.com',
            'password' => Hash::make('12345678')
        ]);
        $user->assignRole([$dyRole->id]);



        // Clerk Seeder ##
        $clerkRole = Role::updateOrCreate(['name'=> 'Clerk']);
        $permissions = Permission::where('group', 'audit')->pluck('id','id');
        $clerkRole->syncPermissions($permissions);

        $user = User::updateOrCreate([
            'email' => 'clerk@gmail.com'
        ],[
            'first_name' => 'Clerk',
            'middle_name' => '',
            'last_name' => '',
            'gender' => 'male',
            'mobile' => '9999999997',
            'department_id' => '3',
            'username' => 'clerk@gmail.com',
            'email' => 'clerk@gmail.com',
            'password' => Hash::make('12345678')
        ]);
        $user->assignRole([$clerkRole->id]);



        // Local Fund Seeder ##
        $localFundRole = Role::updateOrCreate(['name'=> 'Local Fund']);
        $permissions = Permission::pluck('id','id')->all();
        $localFundRole->syncPermissions($permissions);

        $user = User::updateOrCreate([
            'email' => 'localfund@gmail.com'
        ],[
            'first_name' => 'Local Fund',
            'middle_name' => '',
            'last_name' => '',
            'gender' => 'male',
            'mobile' => '9999999998',
            'department_id' => '1',
            'username' => 'localfund@gmail.com',
            'email' => 'localfund@gmail.com',
            'password' => Hash::make('12345678')
        ]);
        $user->assignRole([$localFundRole->id]);



        // AG Audit Seeder ##
        $agRole = Role::updateOrCreate(['name'=> 'AG Audit']);
        $permissions = Permission::pluck('id','id')->all();
        $agRole->syncPermissions($permissions);

        $user = User::updateOrCreate([
            'email' => 'agaudit@gmail.com'
        ],[
            'first_name' => 'AG',
            'middle_name' => 'Audit',
            'last_name' => '',
            'gender' => 'male',
            'mobile' => '9999999999',
            'department_id' => '1',
            'username' => 'agaudit@gmail.com',
            'email' => 'agaudit@gmail.com',
            'password' => Hash::make('12345678')
        ]);
        $user->assignRole([$agRole->id]);

    }
}
