<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\Task;
use App\Models\User;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $advisor = User::where('role', 'advisor')->first();
        $leader1 = User::where('email', 'leader1@example.com')->first();
        $member1 = User::where('email', 'member1@example.com')->first();
        $member2 = User::where('email', 'member2@example.com')->first();

        $task1 = Task::where('title', 'Build face detection module')->first();
        $task2 = Task::where('title', 'Implement pathfinding algorithm')->first();
        $task3 = Task::where('title', 'Implement video conferencing')->first();
        $task4 = Task::where('title', 'Design mobile UI mockups')->first();

        // Comments on face detection task
        if ($task1) {
            Comment::create([
                'task_id' => $task1->id,
                'user_id' => $leader1->id,
                'body' => 'Great progress! Make sure to test with different lighting conditions.',
            ]);

            Comment::create([
                'task_id' => $task1->id,
                'user_id' => $member1->id,
                'body' => 'I\'ve tested with 50+ sample faces. Detection accuracy is around 92% so far.',
            ]);

            Comment::create([
                'task_id' => $task1->id,
                'user_id' => $advisor->id,
                'body' => 'Excellent work. Document your testing methodology for the final report.',
            ]);
        }

        // Comments on pathfinding task
        if ($task2) {
            Comment::create([
                'task_id' => $task2->id,
                'user_id' => $leader1->id,
                'body' => 'Have you considered using Dijkstra\'s algorithm instead of A*?',
            ]);

            Comment::create([
                'task_id' => $task2->id,
                'user_id' => $member1->id,
                'body' => 'A* is faster for our use case since we have heuristic data from building layouts.',
            ]);
        }

        // Comments on video conferencing task
        if ($task3) {
            Comment::create([
                'task_id' => $task3->id,
                'user_id' => $advisor->id,
                'body' => 'Please ensure the solution complies with university privacy policies.',
            ]);

            Comment::create([
                'task_id' => $task3->id,
                'user_id' => $member2->id,
                'body' => 'I\'m evaluating Jitsi Meet API. It\'s open-source and integrates well with our stack.',
            ]);
        }

        // Comments on UI mockup task
        if ($task4) {
            Comment::create([
                'task_id' => $task4->id,
                'user_id' => $leader1->id,
                'body' => 'Remember to follow Material Design guidelines for consistency.',
            ]);
        }
    }
}
