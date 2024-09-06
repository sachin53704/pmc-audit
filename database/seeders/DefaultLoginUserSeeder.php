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
        $superAdminRole = Role::updateOrCreate(['name' => 'Super Admin']);
        $permissions = Permission::pluck('id', 'id')->all();
        $superAdminRole->syncPermissions($permissions);

        $superAdmin = User::updateOrCreate([
            'email' => 'superadmin@gmail.com'
        ], [
            'first_name' => 'Super',
            'middle_name' => '',
            'last_name' => 'Admin',
            'gender' => 'male',
            'mobile' => '9999999991',
            'email' => 'superadmin@gmail.com',
            'username' => 'superadmin@gmail.com',
            'password' => Hash::make('12345678'),
        ]);
        $superAdmin->assignRole([$superAdminRole->id]);

        $superAdmin->syncPermissions(['dashboard.view', 'audit-para-category.index', 'audit-para-category.create', 'audit-para-category.edit', 'audit-type.index', 'audit-type.create', 'audit-type.edit', 'severity.index', 'severity.create', 'severity.edit', 'zone.index', 'zone.create', 'zone.edit', 'users.view', 'users.create', 'users.edit', 'users.delete', 'users.toggle_status', 'users.change_password', 'roles.view', 'roles.create', 'roles.edit', 'roles.delete', 'roles.assign', 'fiscal_years.view', 'fiscal_years.create', 'fiscal_years.edit', 'fiscal_years.delete', 'departments.view', 'departments.create', 'departments.edit', 'departments.delete', 'audit.view', 'audit.create', 'audit.edit', 'audit.delete', 'audit_list.pending', 'audit_list.approved', 'audit_list.rejected', 'audit_list.assign', 'assigned_audit.view', 'send_letter.department', 'department_letter.view', 'objection.create', 'objection.store', 'compliance.create', 'compliance.store', 'answered-questions.view', 'draft-review.view', 'report.final-report', 'report.para-audit', 'report.complience-answer', 'report.department', 'receipt.view', 'receipt.create', 'payment-receipt.view', 'payment-receipt.create', 'receipt.pending-list', 'receipt.approve-list', 'receipt.reject-list', 'receipt.approve', 'receipt.reject', 'payment-receipt.pending-list', 'payment-receipt.approve-list', 'payment-receipt.reject-list', 'payment-receipt.approve', 'payment-receipt.reject', 'diary.index', 'diary.create', 'diary.edit', 'diary.delete', 'diary.view']);




        // Admin Seeder ##
        $adminRole = Role::updateOrCreate(['name' => 'Admin']);
        $adminRole->syncPermissions(['dashboard.view', 'users.view', 'users.create', 'users.edit', 'users.delete', 'users.toggle_status', 'users.change_password', 'roles.view', 'roles.create', 'roles.edit', 'roles.delete', 'roles.assign', 'fiscal_years.view', 'fiscal_years.create', 'fiscal_years.edit', 'fiscal_years.delete', 'departments.view', 'departments.create', 'departments.edit', 'departments.delete', 'audit.view', 'audit.create', 'audit.edit', 'audit.delete', 'audit_list.pending', 'audit_list.approved', 'audit_list.rejected', 'audit_list.assign', 'assigned_audit.view', 'send_letter.department', 'department_letter.view', 'objection.create', 'objection.store', 'compliance.create', 'compliance.store', 'answered-questions.view', 'draft-review.view', 'report.final-report', 'report.para-audit', 'report.complience-answer', 'report.department', 'receipt.view', 'receipt.create', 'payment-receipt.view', 'payment-receipt.create', 'receipt.pending-list', 'receipt.approve-list', 'receipt.reject-list', 'receipt.approve', 'receipt.reject', 'payment-receipt.pending-list', 'payment-receipt.approve-list', 'payment-receipt.reject-list', 'payment-receipt.approve', 'payment-receipt.reject', 'diary.index', 'diary.create', 'diary.edit', 'diary.delete', 'diary.view']);

        $admin = User::updateOrCreate([
            'email' => 'admin@gmail.com'
        ], [
            'first_name' => 'Admin',
            'middle_name' => '',
            'last_name' => '',
            'gender' => 'male',
            'mobile' => '9999999992',
            'email' => 'admin@gmail.com',
            'username' => 'admin@gmail.com',
            'password' => Hash::make('12345678')
        ]);
        $admin->assignRole([$adminRole->id]);



        // Department Seeder ##
        $departmentRole = Role::updateOrCreate(['name' => 'Department']);
        $departmentRole->syncPermissions(['department_letter.view', 'compliance.create', 'compliance.store', 'receipt.view', 'receipt.create', 'payment-receipt.view', 'payment-receipt.create']);

        $department = User::updateOrCreate([
            'email' => 'department@gmail.com'
        ], [
            'first_name' => 'Department',
            'middle_name' => '',
            'last_name' => '',
            'gender' => 'male',
            'mobile' => '9999999993',
            'email' => 'department@gmail.com',
            'department_id' => '1',
            'username' => 'department@gmail.com',
            'password' => Hash::make('12345678')
        ]);
        $department->assignRole([$departmentRole->id]);



        // Auditor Seeder ##
        $auditorRole = Role::updateOrCreate(['name' => 'Auditor']);
        $auditorRole->syncPermissions(['assigned_audit.view', 'send_letter.department', 'objection.create', 'objection.store', 'answered-questions.view', 'diary.index', 'diary.create', 'diary.edit', 'diary.delete', 'diary.view', 'report.para-audit', 'report.complience-answer', 'report.department']);

        $user = User::updateOrCreate([
            'email' => 'auditor@gmail.com'
        ], [
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
        $mcaRole = Role::updateOrCreate(['name' => 'MCA']);
        $mcaRole->syncPermissions(['audit_list.approved', 'audit_list.assign', 'audit_list.pending', 'audit_list.rejected', 'draft-review.view', 'report.final-report', 'report.para-audit', 'report.complience-answer', 'report.department', 'payment-receipt.pending-list', 'payment-receipt.approve-list', 'payment-receipt.reject-list', 'payment-receipt.approve', 'payment-receipt.reject', 'diary.index', 'diary.create', 'diary.edit', 'diary.delete', 'diary.view']);

        $user = User::updateOrCreate([
            'email' => 'mca@gmail.com'
        ], [
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



        // DY MCA Seeder ##
        $dyRole = Role::updateOrCreate(['name' => 'DY MCA']);
        $dyRole->syncPermissions(['audit_list.approved', 'audit_list.pending', 'audit_list.rejected', 'draft-review.view', 'report.final-report', 'report.para-audit', 'report.complience-answer', 'report.department', 'payment-receipt.pending-list', 'payment-receipt.approve-list', 'payment-receipt.reject-list', 'payment-receipt.approve', 'payment-receipt.reject', 'diary.index', 'diary.create', 'diary.edit', 'diary.delete', 'diary.view', 'receipt.pending-list', 'receipt.approve-list', 'receipt.reject-list', 'receipt.approve', 'receipt.reject']);

        $user = User::updateOrCreate([
            'email' => 'dymca@gmail.com'
        ], [
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
        $clerkRole = Role::updateOrCreate(['name' => 'Clerk']);
        $clerkRole->syncPermissions(['audit.view', 'audit.create', 'audit.edit', 'audit.delete']);

        $user = User::updateOrCreate([
            'email' => 'clerk@gmail.com'
        ], [
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
        $localFundRole = Role::updateOrCreate(['name' => 'Local Fund']);
        $localFundRole->syncPermissions(['dashboard.view', 'users.view', 'users.create', 'users.edit', 'users.delete', 'users.toggle_status', 'users.change_password', 'roles.view', 'roles.create', 'roles.edit', 'roles.delete', 'roles.assign', 'fiscal_years.view', 'fiscal_years.create', 'fiscal_years.edit', 'fiscal_years.delete', 'departments.view', 'departments.create', 'departments.edit', 'departments.delete', 'audit.view', 'audit.create', 'audit.edit', 'audit.delete', 'audit_list.pending', 'audit_list.approved', 'audit_list.rejected', 'audit_list.assign', 'assigned_audit.view', 'send_letter.department', 'department_letter.view', 'objection.create', 'objection.store', 'compliance.create', 'compliance.store', 'answered-questions.view', 'draft-review.view', 'report.final-report', 'report.para-audit', 'report.complience-answer', 'report.department', 'receipt.view', 'receipt.create', 'payment-receipt.view', 'payment-receipt.create', 'receipt.pending-list', 'receipt.approve-list', 'receipt.reject-list', 'receipt.approve', 'receipt.reject', 'payment-receipt.pending-list', 'payment-receipt.approve-list', 'payment-receipt.reject-list', 'payment-receipt.approve', 'payment-receipt.reject', 'diary.index', 'diary.create', 'diary.edit', 'diary.delete', 'diary.view']);

        $user = User::updateOrCreate([
            'email' => 'localfund@gmail.com'
        ], [
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
        $agRole = Role::updateOrCreate(['name' => 'AG Audit']);
        $agRole->syncPermissions(['dashboard.view', 'users.view', 'users.create', 'users.edit', 'users.delete', 'users.toggle_status', 'users.change_password', 'roles.view', 'roles.create', 'roles.edit', 'roles.delete', 'roles.assign', 'fiscal_years.view', 'fiscal_years.create', 'fiscal_years.edit', 'fiscal_years.delete', 'departments.view', 'departments.create', 'departments.edit', 'departments.delete', 'audit.view', 'audit.create', 'audit.edit', 'audit.delete', 'audit_list.pending', 'audit_list.approved', 'audit_list.rejected', 'audit_list.assign', 'assigned_audit.view', 'send_letter.department', 'department_letter.view', 'objection.create', 'objection.store', 'compliance.create', 'compliance.store', 'answered-questions.view', 'draft-review.view', 'report.final-report', 'report.para-audit', 'report.complience-answer', 'report.department', 'receipt.view', 'receipt.create', 'payment-receipt.view', 'payment-receipt.create', 'receipt.pending-list', 'receipt.approve-list', 'receipt.reject-list', 'receipt.approve', 'receipt.reject', 'payment-receipt.pending-list', 'payment-receipt.approve-list', 'payment-receipt.reject-list', 'payment-receipt.approve', 'payment-receipt.reject', 'diary.index', 'diary.create', 'diary.edit', 'diary.delete', 'diary.view']);

        $user = User::updateOrCreate([
            'email' => 'agaudit@gmail.com'
        ], [
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



        // Dy-Auditor Seeder ##
        $dyAuditorRole = Role::updateOrCreate(['name' => 'DY Auditor']);
        $dyAuditorRole->syncPermissions(['receipt.pending-list', 'receipt.approve-list', 'receipt.reject-list', 'receipt.approve', 'receipt.reject', 'payment-receipt.pending-list', 'payment-receipt.approve-list', 'payment-receipt.reject-list', 'payment-receipt.approve', 'payment-receipt.reject']);

        $user = User::updateOrCreate([
            'email' => 'dyauditor@gmail.com'
        ], [
            'first_name' => 'DY',
            'middle_name' => 'Auditor',
            'last_name' => '',
            'gender' => 'male',
            'mobile' => '9999912345',
            'auditor_no' => '123485',
            'username' => 'dyauditor@gmail.com',
            'email' => 'dyauditor@gmail.com',
            'password' => Hash::make('12345678')
        ]);
        $user->assignRole([$dyAuditorRole->id]);
    }
}
