<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('master_configs')->insert([
            [
                'configName' => 'timeSlotDuration',
                'configValue' => 60,
                'configDesc' => 'Time slot duration (in minutes)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'configName' => 'bookStart',
                'configValue' => '00:00',
                'configDesc' => 'Allow user to book from:',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'configName' => 'bookEnd',
                'configValue' => '23:59',
                'configDesc' => 'Allow user to book until:',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'configName' => 'days',
                'configValue' => '["monday","tuesday","wednesday","thursday","friday"]',
                'configDesc' => 'Day availability:',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'configName' => 'masterEvent',
                'configValue' => 'Seminar;Gathering',
                'configDesc' => 'List of event type, separated by semi-colon (;)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'configName' => 'masterFaculty',
                'configValue' => 'School of Design;Teachers College',
                'configDesc' => 'List of faculty, separated by semi-colon (;)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'configName' => 'activeTerm',
                'configValue' => '',
                'configDesc' => 'Currently active term',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
