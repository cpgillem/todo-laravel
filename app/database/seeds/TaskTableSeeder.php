<?php

class TaskTableSeeder extends Seeder {
  public function run() {
    DB::table('tasks')->delete();

    $user1 = User::create([
      'email' => 'tasktester@test.com',
      'password' => Hash::make('test')
    ]);

    $task1 = Task::create([
      'description' => 'test task 1',
      'user' => $user1->id
    ]);

    $task2 = Task::create([
      'description' => 'test task 2',
      'user' => $user1->id
    ]);

    $task1->dependencies()->attach($task2->id);

    $task3 = Task::create([
      'description' => 'test task 3',
      'user' => $user1->id
    ]);

    $task2->dependencies()->attach($task3->id);
  }
}

