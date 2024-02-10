<?php

namespace App\Http\Resources;

use App\Enums\TaskStatusEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         =>$this->id,
            'title'      => $this->title,
            'detail'     =>$this->detail,
            'slug'       => $this->slug,
            'status'     =>TaskStatusEnum::getDescription($this->status),
            'start_date' =>$this->start_date,
        ];
    }
}