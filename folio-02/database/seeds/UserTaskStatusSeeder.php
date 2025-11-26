<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class UserTaskStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            'Assigned',
            'In Progress',
            'Completed',
            'Sign-off',
        ];

        foreach ($statuses as $status) {
            DB::table('lookup_user_tasks_status')->updateOrInsert(
                ['description' => $status],
            );
        }
    }
}
