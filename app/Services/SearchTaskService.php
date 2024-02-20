<?php

namespace App\Services;

use App\Models\Task;

class SearchTaskService
{
    public static function searchTasks($text)
    {
        $tasks = Task::where('title' , 'like' , "%$text%")->get();
        return $tasks;
    }

}
