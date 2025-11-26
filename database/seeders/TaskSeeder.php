<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $project1 = Project::where('name', 'AI-Powered Student Attendance System')->first();
        $project2 = Project::where('name', 'Smart Campus Navigation Mobile App')->first();
        $project3 = Project::where('name', 'Online Learning Management System')->first();

        $member1 = User::where('email', 'member1@example.com')->first();
        $member2 = User::where('email', 'member2@example.com')->first();
        $member3 = User::where('email', 'member3@example.com')->first();

        // Project 1 Tasks
        Task::create([
            'project_id' => $project1->id,
            'title' => 'Research facial recognition algorithms',
            'description' => 'Review existing facial recognition libraries and compare accuracy, performance, and integration complexity.',
            'assignee_id' => $member1->id,
            'status' => 'done',
            'order' => 1,
        ]);

        Task::create([
            'project_id' => $project1->id,
            'title' => 'Set up development environment',
            'description' => 'Install Python, TensorFlow, OpenCV, and configure database for storing attendance records.',
            'assignee_id' => $member2->id,
            'status' => 'done',
            'order' => 2,
        ]);

        Task::create([
            'project_id' => $project1->id,
            'title' => 'Build face detection module',
            'description' => 'Implement real-time face detection using OpenCV and test with sample webcam footage.',
            'assignee_id' => $member1->id,
            'status' => 'in-progress',
            'order' => 1,
        ]);

        Task::create([
            'project_id' => $project1->id,
            'title' => 'Design database schema',
            'description' => 'Create tables for students, courses, attendance logs, and facial embeddings.',
            'assignee_id' => $member3->id,
            'status' => 'in-progress',
            'order' => 2,
        ]);

        Task::create([
            'project_id' => $project1->id,
            'title' => 'Create admin dashboard',
            'description' => 'Design and implement web interface for viewing attendance reports and managing student data.',
            'assignee_id' => $member2->id,
            'status' => 'todo',
            'order' => 1,
        ]);

        // Project 2 Tasks
        Task::create([
            'project_id' => $project2->id,
            'title' => 'Map campus buildings',
            'description' => 'Create digital floor plans for all major buildings and mark key locations (classrooms, restrooms, offices).',
            'assignee_id' => $member1->id,
            'status' => 'done',
            'order' => 3,
        ]);

        Task::create([
            'project_id' => $project2->id,
            'title' => 'Implement pathfinding algorithm',
            'description' => 'Develop A* algorithm for indoor navigation between two points within buildings.',
            'assignee_id' => $member3->id,
            'status' => 'in-progress',
            'order' => 3,
        ]);

        Task::create([
            'project_id' => $project2->id,
            'title' => 'Design mobile UI mockups',
            'description' => 'Create Figma designs for main navigation screen, building details, and event listings.',
            'assignee_id' => $member2->id,
            'status' => 'todo',
            'order' => 2,
        ]);

        Task::create([
            'project_id' => $project2->id,
            'title' => 'Integrate campus event API',
            'description' => 'Connect to university event management system to display real-time events on the app.',
            'assignee_id' => $member1->id,
            'status' => 'todo',
            'order' => 3,
        ]);

        // Project 3 Tasks
        Task::create([
            'project_id' => $project3->id,
            'title' => 'Set up Laravel backend',
            'description' => 'Initialize Laravel project with authentication, models for courses, assignments, and users.',
            'assignee_id' => $member2->id,
            'status' => 'done',
            'order' => 4,
        ]);

        Task::create([
            'project_id' => $project3->id,
            'title' => 'Implement video conferencing',
            'description' => 'Integrate WebRTC or third-party API (Zoom/Jitsi) for live video classes.',
            'assignee_id' => $member3->id,
            'status' => 'in-progress',
            'order' => 4,
        ]);

        Task::create([
            'project_id' => $project3->id,
            'title' => 'Build assignment submission module',
            'description' => 'Allow students to upload files, view deadlines, and receive feedback from instructors.',
            'assignee_id' => $member1->id,
            'status' => 'todo',
            'order' => 4,
        ]);

        Task::create([
            'project_id' => $project3->id,
            'title' => 'Create grade tracking dashboard',
            'description' => 'Display student grades, course progress, and generate downloadable reports.',
            'assignee_id' => $member2->id,
            'status' => 'todo',
            'order' => 5,
        ]);

        Task::create([
            'project_id' => $project3->id,
            'title' => 'Add discussion forum feature',
            'description' => 'Implement threaded discussion boards for course-specific Q&A and announcements.',
            'assignee_id' => $member3->id,
            'status' => 'todo',
            'order' => 6,
        ]);
    }
}
