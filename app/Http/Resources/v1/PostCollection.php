<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PostCollection extends ResourceCollection
{

    public function toArray($request)
    {

        return [
            'data' => $this->collection->map(function ($item) {
                return [
                    'id' => $this->id,
                    'user_id' => $this->user_id,
                    'title' => $item->title,
                    'body' => $item->image
                ];
            })
        ];
    }
}
