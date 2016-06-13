<?php

class UserTableSeeder extends Seeder {
  public function run() {
    DB::table('users')->delete();

    $user1 = User::create([
      'email' => 'test@test.com',
      'password' => Hash::make('test')
    ]);

    $user2 = User::create([
      'email' => 'test2@test.com',
      'password' => Hash::make('test')
    ]);

    $task1 = Task::create([
      'description' => 'owned by user 1',
      'user' => $user1->id
    ]);

    $task2 = Task::create([
      'description' => 'owned by user 2',
      'user' => $user2->id
    ]);
  }
}
