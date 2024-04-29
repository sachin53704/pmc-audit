<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
    }
}
