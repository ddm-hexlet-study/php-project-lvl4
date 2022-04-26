<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $initialValues = [
            'новый',
            'в работе',
            'на тестировании',
            'завершен'
        ];
        array_walk($initialValues, function ($item) {
            DB::table('task_statuses')->insert([
                'name' => $item,
                'created_at' => now()
            ]);
        });
    }
}
