<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'id' => 1,
                'name' => 'dashboard.view',
                'group' => 'dashboard',
            ],
            [
                'id' => 2,
                'name' => 'users.view',
                'group' => 'users',
            ],
            [
                'id' => 3,
                'name' => 'users.create',
                'group' => 'users',
            ],
            [
                'id' => 4,
                'name' => 'users.edit',
                'group' => 'users',
            ],
            [
                'id' => 5,
                'name' => 'users.delete',
                'group' => 'users',
            ],
            [
                'id' => 6,
                'name' => 'users.toggle_status',
                'group' => 'users',
            ],
            [
                'id' => 7,
                'name' => 'users.change_password',
                'group' => 'users',
            ],
            [
                'id' => 8,
                'name' => 'roles.view',
                'group' => 'roles',
            ],
            [
                'id' => 9,
                'name' => 'roles.create',
                'group' => 'roles',
            ],
            [
                'id' => 10,
                'name' => 'roles.edit',
                'group' => 'roles',
            ],
            [
                'id' => 11,
                'name' => 'roles.delete',
                'group' => 'roles',
            ],
            [
                'id' => 12,
                'name' => 'roles.assign',
                'group' => 'roles',
            ],
            [
                'id' => 13,
                'name' => 'fiscal_years.view',
                'group' => 'fiscal_years',
            ],
            [
                'id' => 14,
                'name' => 'fiscal_years.create',
                'group' => 'fiscal_years',
            ],
            [
                'id' => 15,
                'name' => 'fiscal_years.edit',
                'group' => 'fiscal_years',
            ],
            [
                'id' => 16,
                'name' => 'fiscal_years.delete',
                'group' => 'fiscal_years',
            ],
            [
                'id' => 17,
                'name' => 'departments.view',
                'group' => 'departments',
            ],
            [
                'id' => 18,
                'name' => 'departments.create',
                'group' => 'departments',
            ],
            [
                'id' => 19,
                'name' => 'departments.edit',
                'group' => 'departments',
            ],
            [
                'id' => 20,
                'name' => 'departments.delete',
                'group' => 'departments',
            ],
            [
                'id' => 21,
                'name' => 'audit.view',
                'group' => 'audit',
            ],
            [
                'id' => 22,
                'name' => 'audit.create',
                'group' => 'audit',
            ],
            [
                'id' => 23,
                'name' => 'audit.edit',
                'group' => 'audit',
            ],
            [
                'id' => 24,
                'name' => 'audit.delete',
                'group' => 'audit',
            ],
            [
                'id' => 25,
                'name' => 'audit_list.pending',
                'group' => 'mca_audit',
            ],
            [
                'id' => 26,
                'name' => 'audit_list.approved',
                'group' => 'mca_audit',
            ],
            [
                'id' => 27,
                'name' => 'audit_list.rejected',
                'group' => 'mca_audit',
            ],
            [
                'id' => 28,
                'name' => 'audit_list.assign',
                'group' => 'mca_audit',
            ],
            [
                'id' => 29,
                'name' => 'assigned_audit.view',
                'group' => 'auditor_audit',
            ],
            [
                'id' => 30,
                'name' => 'send_letter.department',
                'group' => 'auditor_audit',
            ],
            [
                'id' => 31,
                'name' => 'department_letter.view',
                'group' => 'department_audit',
            ],
            [
                'id' => 32,
                'name' => 'objection.create',
                'group' => 'auditor_audit',
            ],
            [
                'id' => 33,
                'name' => 'objection.store',
                'group' => 'auditor_audit',
            ],
            [
                'id' => 34,
                'name' => 'compliance.create',
                'group' => 'department_audit',
            ],
            [
                'id' => 35,
                'name' => 'compliance.store',
                'group' => 'department_audit',
            ],
        ];

        foreach ($permissions as $permission)
        {
            Permission::updateOrCreate([
                'id' => $permission['id']
            ], [
                'id' => $permission['id'],
                'name' => $permission['name'],
                'group' => $permission['group']
            ]);
        }
    }
}
