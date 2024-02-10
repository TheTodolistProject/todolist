<?php

namespace App\Observers;

use App\Enums\TaskStatusEnum;
use App\Models\Project;
use Log;

class ProjectObserver
{
    public function creating(Project $project)
    {
//        Log::info('in creating observer');
    }
    /**
     * Handle the project "created" event.
     */
    public function created(Project $project): void
    {
//      Log::info('in created observer');
    }

    /**
     * Handle the project "updated" event.
     */
    public function updated(project $project): void
    {
        //
    }

    /**
     * Handle the project "deleted" event.
     */
    public function deleted(project $project): void
    {
        //
    }

    /**
     * Handle the project "restored" event.
     */
    public function restored(project $project): void
    {
        //
    }

    /**
     * Handle the project "force deleted" event.
     */
    public function forceDeleted(project $project): void
    {
        //
    }

    public function saving(Project $project)
    {
//        Log::info('in saving observer');
    }
    public function saved(Project $project)
    {
//        Log::info('in saved observer');
//        Log::info($project->tasks()->get());

    }
}
