<?php

class Task extends Eloquent {

  /* Define the table to use. */
  protected $table = 'tasks';

  /* Mass assignable fields */
  protected $fillable = ['description', 'due', 'done', 'user'];

  /* Define a relationship to the users table. */
  public function user() {
    return $this->belongsTo('Users', 'user', 'id');
  }

  /* Define a many-to-many relationship among tasks. */
  public function dependencies() {
    return $this->belongsToMany('Task', 'dependencies', 'task_id', 'dependency_id');
  }

  /* Define a many-to-many relationship to find the tasks that depend on this one. */
  public function parents() {
    return $this->belongsToMany('Task', 'dependencies', 'dependency_id', 'task_id');
  }
}
