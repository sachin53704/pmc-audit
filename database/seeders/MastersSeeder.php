<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AuditType;
use App\Models\Severity;
use App\Models\AuditParaCategory;

class MastersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Seed Department
        $departments = [
            [
                'id' => 1,
                'name' => 'Accounts',
                'is_audit' => '0',
            ],
            [
                'id' => 2,
                'name' => 'Information Technology',
                'is_audit' => '0',
            ],
            [
                'id' => 3,
                'name' => 'Audit',
                'is_audit' => '1',
            ],
            [
                'id' => 4,
                'name' => 'Home Audit',
                'is_audit' => '1',
            ],
        ];

        foreach ($departments as $department) {
            Department::updateOrCreate([
                'id' => $department['id']
            ], [
                'id' => $department['id'],
                'name' => $department['name'],
                'is_audit' => $department['is_audit']
            ]);
        }


        // Audit type master
        $auditTypes = [
            [
                'id' => 1,
                'name' => 'Statutory',
                'status' => 1
            ]
        ];

        foreach ($auditTypes as $auditType) {
            AuditType::updateOrCreate([
                'id' => $auditType['id']
            ], [
                'id' => $auditType['id'],
                'name' => $auditType['name'],
                'status' => $auditType['status']
            ]);
        }

        // Severity master
        $severities = [
            [
                'id' => 1,
                'name' => 'High',
                'status' => 1
            ],
            [
                'id' => 2,
                'name' => 'Medium',
                'status' => 1
            ],
            [
                'id' => 3,
                'name' => 'Low',
                'status' => 1
            ]
        ];

        foreach ($severities as $severity) {
            Severity::updateOrCreate([
                'id' => $severity['id']
            ], [
                'id' => $severity['id'],
                'name' => $severity['name'],
                'status' => $severity['status']
            ]);
        }

        // Audit para Category master
        $severities = [
            [
                'id' => 1,
                'name' => 'Other no',
                'is_amount' => 0,
                'status' => 1
            ],
            [
                'id' => 2,
                'name' => 'Financial loss',
                'is_amount' => 1,
                'status' => 1
            ],
            [
                'id' => 3,
                'name' => 'Recoverable Amount',
                'is_amount' => 1,
                'status' => 1
            ],
            [
                'id' => 4,
                'name' => 'Outstanding Amount',
                'is_amount' => 1,
                'status' => 1
            ],
            [
                'id' => 5,
                'name' => 'Rule Violation Amount',
                'is_amount' => 0,
                'status' => 1
            ],
            [
                'id' => 5,
                'name' => 'Register Not Available',
                'is_amount' => 0,
                'status' => 1
            ]
        ];

        foreach ($severities as $severity) {
            AuditParaCategory::updateOrCreate([
                'id' => $severity['id']
            ], [
                'id' => $severity['id'],
                'name' => $severity['name'],
                'is_amount' => $severity['is_amount'],
                'status' => $severity['status']
            ]);
        }
    }
}
