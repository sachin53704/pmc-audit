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
            [
                'id' => 36,
                'name' => 'answered-questions.view',
                'group' => 'auditor_audit',
            ],
            [
                'id' => 37,
                'name' => 'draft-review.view',
                'group' => 'mca_audit',
            ],
            [
                'id' => 39,
                'name' => 'receipt.view',
                'group' => 'account_receipt',
            ],
            [
                'id' => 40,
                'name' => 'receipt.create',
                'group' => 'account_receipt',
            ],
            [
                'id' => 41,
                'name' => 'payment-receipt.view',
                'group' => 'account_receipt',
            ],
            [
                'id' => 42,
                'name' => 'payment-receipt.create',
                'group' => 'account_receipt',
            ],
            [
                'id' => 43,
                'name' => 'receipt.pending-list',
                'group' => 'dy_auditor',
            ],
            [
                'id' => 44,
                'name' => 'receipt.approve-list',
                'group' => 'dy_auditor',
            ],
            [
                'id' => 45,
                'name' => 'receipt.reject-list',
                'group' => 'dy_auditor',
            ],
            [
                'id' => 46,
                'name' => 'receipt.approve',
                'group' => 'dy_auditor',
            ],
            [
                'id' => 47,
                'name' => 'receipt.reject',
                'group' => 'dy_auditor',
            ],
            [
                'id' => 48,
                'name' => 'payment-receipt.pending-list',
                'group' => 'dy_auditor',
            ],
            [
                'id' => 49,
                'name' => 'payment-receipt.approve-list',
                'group' => 'dy_auditor',
            ],
            [
                'id' => 50,
                'name' => 'payment-receipt.reject-list',
                'group' => 'dy_auditor',
            ],
            [
                'id' => 51,
                'name' => 'payment-receipt.approve',
                'group' => 'dy_auditor',
            ],
            [
                'id' => 52,
                'name' => 'payment-receipt.reject',
                'group' => 'dy_auditor',
            ],
            [
                'id' => 53,
                'name' => 'diary.index',
                'group' => 'diary',
            ],
            [
                'id' => 54,
                'name' => 'diary.create',
                'group' => 'diary',
            ],
            [
                'id' => 55,
                'name' => 'diary.edit',
                'group' => 'diary',
            ],
            [
                'id' => 56,
                'name' => 'diary.delete',
                'group' => 'diary',
            ],
            [
                'id' => 57,
                'name' => 'diary.view',
                'group' => 'diary',
            ],
            [
                'id' => 58,
                'name' => 'report.para-audit',
                'group' => 'report',
            ],
            [
                'id' => 59,
                'name' => 'report.complience-answer',
                'group' => 'report',
            ],
            [
                'id' => 60,
                'name' => 'report.department',
                'group' => 'report',
            ],
            [
                'id' => 61,
                'name' => 'audit-para-category.index',
                'group' => 'Audit Para Category'
            ],
            [
                'id' => 62,
                'name' => 'audit-para-category.create',
                'group' => 'Audit Para Category'
            ],
            [
                'id' => 63,
                'name' => 'audit-para-category.edit',
                'group' => 'Audit Para Category'
            ],
            [
                'id' => 64,
                'name' => 'audit-type.index',
                'group' => 'Audit Type'
            ],
            [
                'id' => 65,
                'name' => 'audit-type.create',
                'group' => 'Audit Type'
            ],
            [
                'id' => 66,
                'name' => 'audit-type.edit',
                'group' => 'Audit Type'
            ],
            [
                'id' => 67,
                'name' => 'severity.index',
                'group' => 'Severity'
            ],
            [
                'id' => 68,
                'name' => 'severity.create',
                'group' => 'Severity'
            ],
            [
                'id' => 69,
                'name' => 'severity.edit',
                'group' => 'Severity'
            ],
            [
                'id' => 70,
                'name' => 'zone.index',
                'group' => 'Zone'
            ],
            [
                'id' => 71,
                'name' => 'zone.create',
                'group' => 'Zone'
            ],
            [
                'id' => 72,
                'name' => 'zone.edit',
                'group' => 'Zone'
            ],
            [
                'id' => 73,
                'name' => 'report.audit-para-summary-report',
                'group' => 'Report'
            ],
            [
                'id' => 38,
                'name' => 'report.final-report',
                'group' => 'Report'
            ],
            [
                'id' => 75,
                'name' => 'hmm-status.view',
                'group' => 'HMM Status'
            ],
            [
                'id' => 76,
                'name' => 'report.para-current-status',
                'group' => 'Report'
            ],
            [
                'id' => 77,
                'name' => 'working-day.index',
                'group' => 'Working Day'
            ],
            [
                'id' => 78,
                'name' => 'working-day.create',
                'group' => 'Working Day'
            ],
            [
                'id' => 79,
                'name' => 'working-day.edit',
                'group' => 'Working Day'
            ]
        ];

        foreach ($permissions as $permission) {
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
