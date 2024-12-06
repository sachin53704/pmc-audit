<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AuditType;
use App\Models\Severity;
use App\Models\AuditParaCategory;
use App\Models\FiscalYear;
use App\Models\WorkingDay;
use App\Models\Setting;

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
                'initial' => 'Acc',
                'is_audit' => '0',
            ],
            [
                'id' => 2,
                'name' => 'Information Technology',
                'initial' => 'IT',
                'is_audit' => '0',
            ],
            [
                'id' => 3,
                'name' => 'Audit',
                'initial' => 'Audit',
                'is_audit' => '1',
            ],
            [
                'id' => 4,
                'name' => 'Home Audit',
                'initial' => 'Home Audit',
                'is_audit' => '1',
            ],
        ];

        foreach ($departments as $department) {
            Department::updateOrCreate([
                'id' => $department['id']
            ], [
                'id' => $department['id'],
                'name' => $department['name'],
                'initial' => $department['initial'],
                'is_audit' => $department['is_audit']
            ]);
        }

        //Seed Department
        $settings = [
            [
                'id' => 1,
                'name' => 'outward_no',
                'value' => '1',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate([
                'id' => $setting['id']
            ], [
                'id' => $setting['id'],
                'name' => $setting['name'],
                'value' => $setting['value']
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
        $auditParaCategory = [
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
            ],
            [
                'id' => 5,
                'name' => 'Involve Amount',
                'is_amount' => 1,
                'status' => 1
            ]
        ];

        foreach ($auditParaCategory as $auditParaCat) {
            AuditParaCategory::updateOrCreate([
                'id' => $auditParaCat['id']
            ], [
                'id' => $auditParaCat['id'],
                'name' => $auditParaCat['name'],
                'is_amount' => $auditParaCat['is_amount'],
                'status' => $auditParaCat['status']
            ]);
        }

        // Financial year master
        $financialYears = [
            [
                'id' => 1,
                'name' => "202425",
                'status' => 1,
                'from_year' => "2024-04-01",
                'to_year' => "2025-03-01",
            ],
            [
                'id' => 2,
                'name' => "202526",
                'status' => 0,
                'from_year' => "2025-04-01",
                'to_year' => "2026-03-01",
            ]
        ];

        foreach ($financialYears as $financialYear) {
            FiscalYear::updateOrCreate([
                'id' => $financialYear['id']
            ], [
                'id' => $financialYear['id'],
                'name' => $financialYear['name'],
                'status' => $financialYear['status'],
                'from_year' => $financialYear['from_year'],
                'to_year' => $financialYear['to_year'],
            ]);
        }



        // Audit type master
        $workingDays = [
            [
                'id' => 1,
                'name' => 'Sunday',
                'status' => 1
            ],
            [
                'id' => 2,
                'name' => 'Monday',
                'status' => 1
            ],
            [
                'id' => 3,
                'name' => 'Tuesday',
                'status' => 1
            ],
            [
                'id' => 4,
                'name' => 'Wednesday',
                'status' => 1
            ],
            [
                'id' => 5,
                'name' => 'Thursday',
                'status' => 1
            ],
            [
                'id' => 6,
                'name' => 'Friday',
                'status' => 1
            ],
            [
                'id' => 7,
                'name' => 'Saturday',
                'status' => 1
            ]
        ];

        foreach ($workingDays as $workingDay) {
            WorkingDay::updateOrCreate([
                'id' => $workingDay['id']
            ], [
                'id' => $workingDay['id'],
                'name' => $workingDay['name'],
                'status' => $workingDay['status']
            ]);
        }
    }
}
