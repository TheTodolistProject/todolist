<?php

namespace App\Http\Resources;

use App\Enums\TaskStatusEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $completed = $this->tasks()->whereStatus(TaskStatusEnum::Finished)->count();
        $count = collect(TaskStatusEnum::getValues())->count();
        $progress = 0;
        if ($completed){
            $progress = ($completed*100)/$count;
        }

        return [
          'id'            => $this->id,
          'title'         => $this->title,
          'detail'        => $this->detail,
          'progress'      => $progress,
          'start_date'    => $this->start_date,
          'deadline_date' => $this->deadline_date,
          'tasks'         => TaskResource::collection($this->tasks),
          'users'         => UserInformationResource::collection($this->users)
        ];
    }
}
