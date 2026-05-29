<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ProjectTypesSeeder extends Seeder
{
     public function run(): void
    {
        $types = ['commercial', 'residential', 'infrastructure'];

        foreach ($types as $type) {
            DB::table('project_types')->insert([
                'name' => $type,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
