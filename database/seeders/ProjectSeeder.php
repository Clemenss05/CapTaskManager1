<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\User;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leader1 = User::where('email', 'leader1@example.com')->first();
        $leader2 = User::where('email', 'leader2@example.com')->first();

        Project::create([
            'name' => 'AI-Powered Student Attendance System',
            'description' => 'Developing a facial recognition-based attendance system using machine learning to automate classroom attendance tracking and generate real-time reports for faculty.',
            'owner_id' => $leader1->id,
        ]);

        Project::create([
            'name' => 'Smart Campus Navigation Mobile App',
            'description' => 'A mobile application that provides indoor navigation, building information, event schedules, and real-time updates for students and visitors navigating the university campus.',
            'owner_id' => $leader2->id,
        ]);

        Project::create([
            'name' => 'Online Learning Management System',
            'description' => 'A comprehensive LMS platform with video conferencing, assignment submission, grade tracking, and collaborative tools designed specifically for hybrid learning environments.',
            'owner_id' => $leader1->id,
        ]);
    }
}
