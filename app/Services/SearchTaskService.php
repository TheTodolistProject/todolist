<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Task;
use function Laravel\Prompts\progress;

class SearchTaskService
{
    public static function searchTasks($text)
    {
        $user = auth()->user();
        if ($user->hasRole(['super_admin' , 'manager'])) {
            $tasks = Task::where('title' , 'like' , "%$text%")
                ->orWhere('detail' ,'like' , "%$text%")->get();
            return $tasks;
        }
        else {
            $tasks1 = Task::where(function ($query) use ($text) {
                $query->where('title', 'like', "%$text%")
                    ->orWhere('detail', 'like', "%$text%");
            })->whereHas('users', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get();

            $projects = $user->projects()->get();
            foreach ($projects as $project) {
                $tasks2 = $project->tasks()->where(function ($query) use ($text) {
                    $query->where('title', 'like', "%$text%")
                        ->orWhere('detail', 'like', "%$text%");
                })->get();
                $tasks = $tasks1->union($tasks2);
            }
            return $tasks;
        }
    }

}
